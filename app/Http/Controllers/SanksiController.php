<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Sanksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Tambahkan ini jika ingin menggunakan Str::lines

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
            'bobot_min' => 'required|integer|min:0',
            'bobot_max' => 'required|integer|min:0|gte:bobot_min', // bobot_max harus lebih besar atau sama dengan bobot_min
            'nama_sanksi' => 'required',
            'pembina' => 'required|string|max:255',
            'keputusan_tindakan' => 'required'
        ]);

        // Mengonversi string dari textarea menjadi array, memisahkan berdasarkan baris baru
        // Jika menggunakan TinyMCE, Anda mungkin perlu mengurai HTML ke dalam array atau menyimpannya sebagai satu string HTML utuh
        $namaSanksiArray = array_map('trim', explode("\n", $request->nama_sanksi));
        $keputusanTindakanArray = array_map('trim', explode("\n", $request->keputusan_tindakan));

        Sanksi::create([
            'kategori_id' => $request->kategori_id,
            'bobot_min' => $request->bobot_min,
            'bobot_max' => $request->bobot_max,
            'nama_sanksi' => $namaSanksiArray, // Simpan sebagai array
            'pembina' => $request->pembina,
            'keputusan_tindakan' => $keputusanTindakanArray, // Simpan sebagai array
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
        // Data nama_sanksi dan keputusan_tindakan sudah menjadi array karena casts di model
        return view('data_pelanggaran.sanksi.edit', compact('sanksi'));
    }

    public function update(Request $request, Sanksi $sanksi)
    {
        $request->validate([
            'bobot_min' => 'required|integer|min:0',
            'bobot_max' => 'required|integer|min:0|gte:bobot_min',
            'nama_sanksi' => 'required',
            'pembina' => 'required|string|max:255',
            'keputusan_tindakan' => 'required'
        ]);

        // Mengonversi string dari textarea menjadi array
        $namaSanksiArray = array_map('trim', explode("\n", $request->nama_sanksi));
        $keputusanTindakanArray = array_map('trim', explode("\n", $request->keputusan_tindakan));

        $sanksi->update([
            'bobot_min' => $request->bobot_min,
            'bobot_max' => $request->bobot_max,
            'nama_sanksi' => $namaSanksiArray, // Update sebagai array
            'pembina' => $request->pembina,
            'keputusan_tindakan' => $keputusanTindakanArray, // Update sebagai array
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
