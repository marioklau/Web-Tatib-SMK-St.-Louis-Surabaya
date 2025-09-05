<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Sanksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

        return view('admin.data_pelanggaran.sanksi.index', compact('sanksi', 'kategori', 'filterKategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.data_pelanggaran.sanksi.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'bobot_min' => 'nullable|integer|min:0',
            'bobot_max' => 'required|integer|min:0|gte:bobot_min',
            'nama_sanksi' => 'required|string',
            'pembina' => 'required|string|max:255',
            'keputusan_tindakan' => 'required|string'
        ]);

        try {
            // Jika bobot_min kosong, set ke 0
            $bobotMin = $request->bobot_min ?? 0;

            Sanksi::create([
                'kategori_id' => $request->kategori_id,
                'bobot_min' => $bobotMin,
                'bobot_max' => $request->bobot_max,
                'nama_sanksi' => array_map('trim', explode("\n", $request->nama_sanksi)),
                'pembina' => $request->pembina,
                'keputusan_tindakan' => array_map('trim', explode("\n", $request->keputusan_tindakan)),
            ]);

            return redirect()->route('sanksi.index')
                ->with('success', 'Sanksi Berhasil Ditambahkan.');
                
        } catch (QueryException $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan sanksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $sanksi = Sanksi::findOrFail($id);
        return view('admin.data_pelanggaran.sanksi.show', compact('sanksi'));
    }

    public function edit(Sanksi $sanksi)
    {
        $kategori = Kategori::all();
        return view('admin.data_pelanggaran.sanksi.edit', compact('sanksi', 'kategori'));
    }

    public function update(Request $request, Sanksi $sanksi)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'bobot_min' => 'nullable|integer|min:0',
            'bobot_max' => 'required|integer|min:0|gte:bobot_min',
            'nama_sanksi' => 'required|string',
            'pembina' => 'required|string|max:255',
            'keputusan_tindakan' => 'required|string'
        ]);

        try {
            // Jika bobot_min kosong, set ke 0
            $bobotMin = $request->bobot_min ?? 0;

            $sanksi->update([
                'kategori_id' => $request->kategori_id,
                'bobot_min' => $bobotMin,
                'bobot_max' => $request->bobot_max,
                'nama_sanksi' => array_map('trim', explode("\n", $request->nama_sanksi)),
                'pembina' => $request->pembina,
                'keputusan_tindakan' => array_map('trim', explode("\n", $request->keputusan_tindakan)),
            ]);

            return redirect()->route('sanksi.index')
                ->with('success', 'Sanksi Berhasil Diperbarui.');
                
        } catch (QueryException $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui sanksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Sanksi $sanksi)
    {
        try {
            $sanksi->delete();
            return redirect()->route('sanksi.index')
                ->with('success', 'Sanksi Pelanggaran Berhasil Dihapus');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1451) {
                return redirect()->back()
                    ->with('error', 'Sanksi pelanggaran tidak dapat dihapus karena sudah terdapat pelanggaran yang terkait.');
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus sanksi: ' . $e->getMessage());
        }
    }
}