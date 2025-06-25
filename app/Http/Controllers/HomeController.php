<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Tahun;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('home'); 
    }

    public function dashboard()
{
    $tahunAktif = Tahun::where('status', 'aktif')->first();

    // Total
    $siswaIds = Pelanggaran::where('tahun_ajaran_id', $tahunAktif->id)
        ->distinct()
        ->pluck('siswa_id');
    
    $totalSiswa = Siswa::whereIn('id', $siswaIds)->count();
    $totalKelas = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->count();
    $totalPelanggaran = Pelanggaran::where('tahun_ajaran_id', $tahunAktif->id)->count();

    // Grafik Pelanggaran per Bulan
    $pelanggaranPerBulan = Pelanggaran::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->where('tahun_ajaran_id', $tahunAktif->id)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    $labels = [];
    $data = [];

    for ($i = 1; $i <= 12; $i++) {
        $labels[] = Carbon::create()->month($i)->translatedFormat('F');
        $found = $pelanggaranPerBulan->firstWhere('bulan', $i);
        $data[] = $found ? $found->total : 0;
    }

    $pelanggaranPerBulanChart = [
        'labels' => $labels,
        'data' => $data,
    ];

    // Top 10 Siswa
    $topSiswaData = Pelanggaran::select('siswa_id', DB::raw('COUNT(*) as total'))
        ->where('tahun_ajaran_id', $tahunAktif->id)
        ->groupBy('siswa_id')
        ->orderByDesc('total')
        ->take(10)
        ->get();

    $topSiswa = [
        'labels' => $topSiswaData->map(function ($item) {
            $siswa = Siswa::find($item->siswa_id);
            return $siswa ? $siswa->nama_siswa : 'Tidak diketahui';
        }),
        'data' => $topSiswaData->pluck('total'),
    ];

    // Top 10 Kelas
    $topKelasData = Pelanggaran::select('kelas.id', 'kelas.kode_kelas', DB::raw('COUNT(*) as total'))
    ->join('siswa', 'pelanggaran.siswa_id', '=', 'siswa.id')
    ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
    ->where('pelanggaran.tahun_ajaran_id', $tahunAktif->id)
    ->groupBy('kelas.id', 'kelas.kode_kelas')
    ->orderByDesc('total')
    ->take(10)
    ->get();

$topKelas = [
    'labels' => $topKelasData->pluck('kode_kelas'),
    'data' => $topKelasData->pluck('total'),
];


    return view('layouts.dashboard', compact(
        'totalSiswa',
        'totalKelas',
        'totalPelanggaran',
        'pelanggaranPerBulanChart',
        'topSiswa',
        'topKelas'
    ));
}

}
