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
            'bobot_max' => 'required|integer|min:0|gte:bobot_min',
            'nama_sanksi' => 'required|string', // Validasi ini untuk memastikan input adalah string (dari textarea)
            'pembina' => 'required|string|max:255',
            'keputusan_tindakan' => 'required|string' // Validasi ini untuk memastikan input adalah string
        ]);

        // Ubah string dari textarea menjadi array berdasarkan baris baru
        // Dengan `protected $casts = ['nama_sanksi' => 'array'];` di model,
        // Laravel akan otomatis mengonversi array ini ke JSON saat disimpan.
        Sanksi::create([
            'kategori_id' => $request->kategori_id,
            'bobot_min' => $request->bobot_min,
            'bobot_max' => $request->bobot_max,
            'nama_sanksi' => array_map('trim', explode("\n", $request->nama_sanksi)),
            'pembina' => $request->pembina,
            'keputusan_tindakan' => array_map('trim', explode("\n", $request->keputusan_tindakan)),
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
            'nama_sanksi' => 'required|string',
            'pembina' => 'required|string|max:255',
            'keputusan_tindakan' => 'required|string'
        ]);

        // Ubah string dari textarea menjadi array berdasarkan baris baru
        $sanksi->update([
            'bobot_min' => $request->bobot_min,
            'bobot_max' => $request->bobot_max,
            'nama_sanksi' => array_map('trim', explode("\n", $request->nama_sanksi)),
            'pembina' => $request->pembina,
            'keputusan_tindakan' => array_map('trim', explode("\n", $request->keputusan_tindakan)),
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
