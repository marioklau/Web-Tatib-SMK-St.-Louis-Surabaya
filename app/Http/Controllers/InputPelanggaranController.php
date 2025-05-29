<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggaran; 
use App\Models\Kategori;
use App\Models\Jenis;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sanksi;

class InputPelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $pelanggaran = Pelanggaran::with('siswa.kelas', 'kategori', 'jenis', 'sanksi')->latest()->get();
        $siswa = Siswa::with('kelas')->get();
        $kategori = Kategori::with('jenis')->get(); // gunakan relasi eager loading
        $sanksi = Sanksi::all();

        return view('input_pelanggaran.index', compact('pelanggaran', 'siswa', 'kategori', 'sanksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kategori_id' => 'required|exists:kategori,id',
            'jenis_id' => 'required|exists:jenis,id',
            'sanksi_id' => 'required|exists:sanksi,id',
        ]);

        Pelanggaran::create($request->all());

        return redirect()->back()->with('success', 'Pelanggaran berhasil ditambahkan.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
