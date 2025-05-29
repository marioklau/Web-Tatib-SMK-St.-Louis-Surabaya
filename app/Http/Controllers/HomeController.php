<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;

class HomeController extends Controller
{
    public function index()
    {
        return view('home'); 
    }

    public function dashboard()
    {
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalPelanggaran = 0; // Ubah ini jika ada model pelanggaran

        return view('layouts.dashboard', compact('totalSiswa', 'totalKelas', 'totalPelanggaran'));
    }
}