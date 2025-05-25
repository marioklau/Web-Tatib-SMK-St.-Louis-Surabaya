<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::latest()->paginate(10);
        return view('siswa.index', compact('siswa'));
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
        return view('siswa.create');
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
        $daftar_kelas = \App\Models\Kelas::all();
        return view('siswa.edit', compact('siswa','daftar_kelas'));
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

