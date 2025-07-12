<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('jenis')->latest()->paginate(10);
        return view('data_pelanggaran.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('data_pelanggaran.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori Berhasil Ditambahkan.');
    }

    public function show($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('data_pelanggaran.kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori) 
    {
        return view('data_pelanggaran.kategori.edit', compact('kategori'));
    }
    
    public function update(Request $request, Kategori $kategori) 
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,'.$kategori->id,
        ]);
    
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);
    
        return redirect()->route('kategori.index')
            ->with('success', 'Kategori Berhasil Diupdate.');
    }

    public function destroy(Kategori $kategori)
    {
        try {
            $kategori->delete();
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori Pelanggaran Berhasil Dihapus');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1451) {
                return redirect()->back()
                    ->with('error', 'Kategori tidak dapat dihapus karena sudah terdapat pelanggaran yang terkait.');
            }
        }
    }
}