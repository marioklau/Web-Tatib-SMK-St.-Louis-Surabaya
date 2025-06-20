<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();

        $filterKategori = $request->query('kategori_id'); // ambil dari query param

        $query = Jenis::with('kategori');

        if ($filterKategori) {
            $query->where('kategori_id', $filterKategori);
        }


        $jenis = $query->paginate(10);

        return view('data_pelanggaran.jenis.index', compact('jenis', 'kategori', 'filterKategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('data_pelanggaran.jenis.create', compact('kategori'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'bentuk_pelanggaran' => 'required|string',
        ]);
    
        Jenis::create([
            'kategori_id' => $request->kategori_id,
            'bentuk_pelanggaran' => $request->bentuk_pelanggaran,
        ]);
    
        return redirect()->route('jenis.index')->with('success', 'Data berhasil ditambahkan.');
    }
    

    public function show($id)
    {
        $kategori = Jenis::findOrFail($id);
        return view('data_pelanggaran.jenis.show', compact('jenis'));
    }

   public function edit(Jenis $jenis) 
   {
       return view('data_pelanggaran.jenis.edit', compact('jenis'));
   }
   
   public function update(Request $request, Jenis $jenis) 
   {
       $request->validate([
           'bentuk_pelanggaran' => 'required|unique:jenis,bentuk_pelanggaran,'.$jenis->id,
       ]);
   
       $jenis->update([
           'bentuk_pelanggaran' => $request->bentuk_pelanggaran,
       ]);
   
       return redirect()->route('jenis.index')
           ->with('success', 'Bentuk Pelanggaran Berhasil Diupdate.');
   }
    public function destroy(Jenis $jenis)
    {
        $jenis->delete();
        return redirect()->route('jenis.index')
            ->with('success', 'Bentuk Pelanggaran Berhasil Dihapus');
    }
}
