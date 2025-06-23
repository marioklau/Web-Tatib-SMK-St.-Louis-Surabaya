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
        'tahun_ajaran' => 'required|string|max:20',
    ]);

    Tahun::create([
        'tahun_ajaran' => $request->tahun_ajaran,
        'status' => 'nonaktif', // default langsung di-set
    ]);
    
    return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
}

     public function aktifkan($id)
    {
        Tahun::where('status', 'aktif')->update(['status' => 'nonaktif']);
        Tahun::where('id', $id)->update(['status' => 'aktif']);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diaktifkan.');
    }


}
