<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Sanksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        if (!$tahunAktif) {
            $kelasList = collect();
            $siswa = new LengthAwarePaginator([], 0, 10); 
            return view('siswa.index', compact('kelasList', 'siswa'))
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }              

        $kelasId = $request->input('kelas_id');
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();

        $query = Siswa::with('kelas')
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->withCount([
                'pelanggaran as ringan_count' => function ($query) {
                    $query->whereHas('kategori', function ($q) {
                        $q->where('nama_kategori', 'RINGAN');
                    });
                },
                'pelanggaran as berat_count' => function ($query) {
                    $query->whereHas('kategori', function ($q) {
                        $q->where('nama_kategori', 'BERAT');
                    });
                },
                'pelanggaran as sangat_berat_count' => function ($query) {
                    $query->whereHas('kategori', function ($q) {
                        $q->where('nama_kategori', 'SANGAT BERAT');
                    });
                },
            ]);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $siswa = $query->paginate(10);

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimport.');
    }

    public function create()
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();

        $siswa = Siswa::with('kelas')
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->withCount([
                'pelanggaran as ringan_count' => fn($q) => $q->whereHas('kategori', fn($k) => $k->where('nama_kategori', 'RINGAN')),
                'pelanggaran as berat_count' => fn($q) => $q->whereHas('kategori', fn($k) => $k->where('nama_kategori', 'BERAT')),
                'pelanggaran as sangat_berat_count' => fn($q) => $q->whereHas('kategori', fn($k) => $k->where('nama_kategori', 'SANGAT BERAT')),
            ])
            ->get();

        $jenis = Jenis::with('kategori')->get();
        $sanksi = Sanksi::all();

        return view('siswa.create', compact('siswa', 'jenis', 'sanksi'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required',
            'nis' => 'required|unique:siswa,nis',
            'jenis_kelamin' => 'required',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);

        Siswa::create([
            'kelas_id'         => $kelas->id,
            'nama_siswa'       => $request->nama_siswa,
            'nis' => [
                        'required',
                        function ($attribute, $value, $fail) use ($request) {
                            $kelas = \App\Models\Kelas::find($request->kelas_id);
                            if (!$kelas) return;
                    
                            $sudahAda = \App\Models\Siswa::where('nis', $value)
                                ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                                ->exists();
                    
                            if ($sudahAda) {
                                $fail('NIS sudah digunakan pada tahun ajaran yang sama.');
                            }
                        },
                    ],            
            'jenis_kelamin'    => $request->jenis_kelamin,
            'tahun_ajaran_id'  => $kelas->tahun_ajaran_id, // PASTIKAN DISET
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa Berhasil Ditambahkan.');
    }

    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        $daftar_kelas = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();

        return view('siswa.edit', compact('siswa', 'daftar_kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required|string|max:255',
            'nis' => 'required|string|max:50',
        ]);

        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::findOrFail($request->kelas_id);

        $siswa->kelas_id = $kelas->id;
        $siswa->nama_siswa = $request->nama_siswa;
        $siswa->nis = $request->nis;
        $siswa->tahun_ajaran_id = $kelas->tahun_ajaran_id; // JAGA KONSISTENSI
        $siswa->save();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        // 1. Cek apakah siswa memiliki relasi dengan data pelanggaran.
        // Method exists() lebih efisien karena tidak me-load semua data.
        if ($siswa->pelanggaran()->exists()) {
            // 2. Jika ADA, batalkan proses hapus dan kembalikan dengan pesan error.
            return redirect()->back()
                ->with('error', 'Gagal! Siswa ini tidak dapat dihapus karena masih memiliki data pelanggaran.');
        }

        // 3. Jika TIDAK ADA, lanjutkan proses hapus.
        try {
            $siswa->delete();
            // Kembalikan dengan pesan sukses.
            return redirect()->route('siswa.index')
                ->with('success', 'Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani jika ada error tak terduga saat proses hapus.
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data siswa: ' . $e->getMessage());
        }
    }
}
