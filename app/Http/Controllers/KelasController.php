<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tahun;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        if (!$tahunAktif) {
            $kelas = collect(); // supaya tidak error di view saat foreach
            return view('kelas.index', compact('kelas'))
                ->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $kelas = Kelas::withCount('siswa')
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->latest()
            ->paginate(10);

        return view('kelas.index', compact('kelas'));
    }


    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|unique:kelas,kode_kelas',
            'nama_kelas' => 'required',
            'wali_kelas' => 'required'
        ]);

        $tahunAktif = Tahun::where('status', 'aktif')->first();
        if (!$tahunAktif) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        Kelas::create([
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
            'tahun_ajaran_id' => $tahunAktif->id
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas Berhasil Ditambahkan.');
    }

    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'kode_kelas' => 'required|unique:kelas,kode_kelas,' . $kelas->id,
            'nama_kelas' => 'required',
            'wali_kelas' => 'required'
        ]);

        $kelas->update([
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas Berhasil Diupdate.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')
            ->with('success', 'Kelas Berhasil Dihapus');
    }
}