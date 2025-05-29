<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('siswa')->latest()->paginate(10);
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
            'nama_kelas' => 'required'
        ]);

        Kelas::create([
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas
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
            'nama_kelas' => 'required'
       ]);
   
       $kelas->update([
            'kode_kelas' => $request->kode_kelas,
            'nama_kelas' => $request->nama_kelas
       ]);
   
       return redirect()->route('kelas.index')
           ->with('success', 'Siswa Berhasil Diupdate.');
   }
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')
            ->with('success', 'Kelas Berhasil Dihapus');
    }
}

