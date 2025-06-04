<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pelanggaran;

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
        $totalPelanggaran = Pelanggaran::count(); 

        return view('layouts.dashboard', compact('totalSiswa', 'totalKelas', 'totalPelanggaran'));
    }
}