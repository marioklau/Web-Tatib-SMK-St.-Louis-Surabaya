<?php

namespace App\Http\Controllers;

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
        $daftar_kelas = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();

        return view('siswa.create', compact('daftar_kelas'));
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
            'nis'              => $request->nis,
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
        $siswa->delete();
        return redirect()->route('siswa.index')
            ->with('success', 'Siswa Berhasil Dihapus');
    }
}
