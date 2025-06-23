<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use App\Models\Tahun;

class TahunAjaranController extends Controller
{

    public function index()
    {
        $tahunAjaran = Tahun::all(); // Atau pakai paginate jika banyak
        return view('tahun_ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('tahun_ajaran.create'); // Pastikan kamu punya view `create.blade.php`
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kategori_id' => 'required|exists:kategori,id',
            'jenis_id' => 'required|exists:jenis,id',
            'sanksi_id' => 'required|exists:sanksi,id',
            'keterangan' => 'nullable|string',
        ]);

        $tahunAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAktif) {
            return back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        Pelanggaran::create([
            'siswa_id' => $request->siswa_id,
            'kategori_id' => $request->kategori_id,
            'jenis_id' => $request->jenis_id,
            'sanksi_id' => $request->sanksi_id,
            'tahun_ajaran_id' => $tahunAktif->id, // wajib isi ini
            'keterangan' => $request->keterangan,
            'status' => 'Belum',
        ]);

        return redirect()->route('input-pelanggaran.index')->with('success', 'Pelanggaran berhasil ditambahkan.');
    }

     public function aktifkan($id)
    {
        Tahun::where('status', 'aktif')->update(['status' => 'nonaktif']);
        Tahun::where('id', $id)->update(['status' => 'aktif']);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }


}
