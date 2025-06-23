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
            $siswa = new LengthAwarePaginator([], 0, 10); // total = 0, perPage = 10
        
            return view('siswa.index', compact('kelasList', 'siswa'))
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }              

        $kelasId = $request->input('kelas_id');

        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();

        $query = Siswa::whereHas('kelas', function ($query) use ($tahunAktif) {
            $query->where('tahun_ajaran_id', $tahunAktif->id);
        });

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
            'jenis_kelamin' => 'required',
        ]);

        Siswa::create([
            'kelas_id' => $request->kelas_id,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
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

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama_siswa' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $siswa->update([
            'kelas_id' => $request->kelas_id,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa Berhasil Diupdate.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')
            ->with('success', 'Siswa Berhasil Dihapus');
    }
}


