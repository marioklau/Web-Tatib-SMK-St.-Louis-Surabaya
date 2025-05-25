<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Sanksi;
use Illuminate\Http\Request;

class SanksiController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();

        $filterKategori = $request->query('kategori_id');

        $query = Sanksi::with('kategori');

        if ($filterKategori) {
            $query->where('kategori_id', $filterKategori);
        }

        $sanksi = $query->get();

        return view('data_pelanggaran.sanksi.index', compact('sanksi', 'kategori', 'filterKategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('data_pelanggaran.sanksi.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'bobot_min' => 'required',
            'bobot_max' => 'required',
            'nama_sanksi' => 'required|unique:sanksi,nama_sanksi',
            'pembina' => 'required',
            'keputusan_tindakan' => 'required'
        ]);

        Sanksi::create([
            'kategori_id' => $request->kategori_id,
            'bobot_min' => $request->bobot_min,
            'bobot_max' => $request->bobot_max,
            'nama_sanksi' => $request->nama_sanksi,
            'pembina' => $request->pembina,
            'keputusan_tindakan' => $request->keputusan_tindakan
        ]);

        return redirect()->route('sanksi.index')
            ->with('success', 'Sanksi Berhasil Ditambahkan.');
    }

    public function show($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        return view('data_pelanggaran.sanksi.show', compact('sanksi'));
    }

   public function edit(Sanksi $sanksi) 
   {
       return view('data_pelanggaran.sanksi.edit', compact('sanksi'));
   }
   
   public function update(Request $request, Sanksi $sanksi) 
   {
       $request->validate([
            'bobot_min' => 'nullable',
            'bobot_max' => 'nullable',
            'nama_sanksi' => 'required|unique:sanksi,nama_sanksi' . $sanksi->id,
            'pembina' => 'required',
            'keputusan_tindakan' => 'required'
       ]);
   
       $sanksi->update([
            'bobot_min' => $request->bobot_min,
            'bobot_max' => $request->bobot_max,
            'nama_sanksi' => $request->nama_sanksi,
            'pembina' => $request->pembina,
            'keputusan_tindakan' => $request->keputusan_tindakan
       ]);
   
       return redirect()->route('sanksi.index')
           ->with('success', 'Sanksi Berhasil Diupdate.');
   }
    public function destroy(Sanksi $sanksi)
    {
        $sanksi->delete();
        return redirect()->route('sanksi.index')
            ->with('success', 'Sanksi Pelanggaran Berhasil Dihapus');
    }

}
