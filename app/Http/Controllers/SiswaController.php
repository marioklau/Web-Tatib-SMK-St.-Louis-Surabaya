<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Sanksi;
use Illuminate\Database\QueryException;
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

        // Jika tidak ada tahun aktif
        if (!$tahunAktif) {
            $kelasList = collect();
            $siswa = new LengthAwarePaginator([], 0, 10);

            // Jika user biasa, arahkan balik
            if (auth()->user()->role === 'user') {
                return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
            }

            // Jika admin, tetap tampilkan view dengan data kosong
            return view('admin.siswa.index', compact('siswa', 'kelasList'));
        }

        // Kalau tahun aktif ada, ambil data seperti biasa
        $kelasId = $request->input('kelas_id');
        $search = $request->input('search');
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

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', '%'.$search.'%')
                ->orWhere('nis', 'like', '%'.$search.'%')
                ->orWhereHas('kelas', function($q) use ($search) {
                    $q->where('nama_kelas', 'like', '%'.$search.'%');
                });
            });
        }

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $siswa = $query->paginate(10);

        if (auth()->user()->role === 'admin') {
            return view('admin.siswa.index', compact('siswa', 'kelasList'));
        } else {
            return view('user.data_siswa', compact('siswa', 'kelasList'));
        }
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

        return view('admin.siswa.create', compact('siswa', 'jenis', 'sanksi'));
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
                            $kelas = Kelas::find($request->kelas_id);
                            if (!$kelas) return;
                    
                            $sudahAda = Siswa::where('nis', $value)
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

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa Berhasil Ditambahkan.');
    }

    public function show($id)
    {
        // Cegah akses jika bukan admin
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('user.data_siswa')
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    $siswa = Siswa::with('kelas')->findOrFail($id);

    return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        $daftar_kelas = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();

        return view('admin.siswa.edit', compact('siswa', 'daftar_kelas'));
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
        try {
            $siswa->delete();
            return redirect()->route('siswa.index')
                ->with('success', 'Siswa Berhasil Dihapus');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1451) {
                return redirect()->back()
                    ->with('error', 'Siswa tidak dapat dihapus karena sudah terdapat pelanggaran yang terkait.');
            }
        }
    }
}
