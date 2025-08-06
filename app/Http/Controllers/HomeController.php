<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Tahun;
use App\Models\Jenis;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();

        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard($tahunAktif);
        } else {
            return $this->userDashboard($tahunAktif);
        }
    }

    protected function adminDashboard($tahunAktif)
    {
        if (!$tahunAktif) {
            return view('admin.dashboard', [
                'totalSiswa' => 0,
                'totalKelas' => 0,
                'totalPelanggaran' => 0,
                'pelanggaranPerBulanChart' => ['labels' => [], 'data' => []],
                'topSiswa' => ['labels' => [], 'data' => []],
                'topKelas' => ['labels' => [], 'data' => []],
                'topJenisPelanggaran' => [],
                'pesan' => 'Belum ada tahun ajaran yang aktif.',
                'tahunAktif' => null,
            ]);
        }

        $totalSiswa = Siswa::where('tahun_ajaran_id', $tahunAktif->id)->count();
        $totalKelas = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->count();
        $totalPelanggaran = Pelanggaran::where('tahun_ajaran_id', $tahunAktif->id)->count();

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

        $topSiswaData = Pelanggaran::with('siswa')
            ->select('siswa_id', DB::raw('COUNT(*) as total'))
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->groupBy('siswa_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $topKelasData = Pelanggaran::select('kelas.id', 'kelas.kode_kelas', DB::raw('COUNT(*) as total'))
            ->join('siswa', 'pelanggaran.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->where('pelanggaran.tahun_ajaran_id', $tahunAktif->id)
            ->groupBy('kelas.id', 'kelas.kode_kelas')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $topJenisPelanggaran = Jenis::withCount(['pelanggaran' => function ($query) use ($tahunAktif) {
            $query->where('tahun_ajaran_id', $tahunAktif->id);
        }])->orderByDesc('pelanggaran_count')->take(5)->get();

        return view('admin.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalKelas' => $totalKelas,
            'totalPelanggaran' => $totalPelanggaran,
            'pelanggaranPerBulanChart' => ['labels' => $labels, 'data' => $data],
            'topSiswa' => [
                'labels' => $topSiswaData->pluck('siswa.nama_siswa'),
                'data' => $topSiswaData->pluck('total'),
            ],
            'topKelas' => [
                'labels' => $topKelasData->pluck('kode_kelas'),
                'data' => $topKelasData->pluck('total'),
            ],
            'topJenisPelanggaran' => [
                'labels' => $topJenisPelanggaran->pluck('bentuk_pelanggaran'),
                'data' => $topJenisPelanggaran->pluck('pelanggaran_count'),
            ],            
            'tahunAktif' => $tahunAktif,
        ]);
    }

    protected function userDashboard($tahunAktif)
    {
        $user = Auth::user();

        if (!$tahunAktif) {
            return view('user.dashboard', [
                'totalSiswa' => 0,
                'totalKelas' => 0,
                'totalPelanggaran' => 0,
                'pelanggaranPerBulanChart' => ['labels' => [], 'data' => []],
                'topSiswa' => ['labels' => [], 'data' => []],
                'topKelas' => ['labels' => [], 'data' => []],
                'topJenisPelanggaran' => [],
                'pesan' => 'Belum ada tahun ajaran yang aktif.',
                'tahunAktif' => null,
            ]);
        }

        // Data dan logika identik dengan admin, bisa dioptimalkan, tapi tetap dibiarkan terpisah jika ingin dikustomisasi lebih lanjut.
        $totalSiswa = Siswa::where('tahun_ajaran_id', $tahunAktif->id)->count();
        $totalKelas = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->count();
        $totalPelanggaran = Pelanggaran::where('tahun_ajaran_id', $tahunAktif->id)->count();

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

        $topSiswaData = Pelanggaran::with('siswa')
            ->select('siswa_id', DB::raw('COUNT(*) as total'))
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->groupBy('siswa_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $topKelasData = Pelanggaran::select('kelas.id', 'kelas.kode_kelas', DB::raw('COUNT(*) as total'))
            ->join('siswa', 'pelanggaran.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->where('pelanggaran.tahun_ajaran_id', $tahunAktif->id)
            ->groupBy('kelas.id', 'kelas.kode_kelas')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $topJenisPelanggaran = Jenis::withCount(['pelanggaran' => function ($query) use ($tahunAktif) {
            $query->where('tahun_ajaran_id', $tahunAktif->id);
        }])->orderByDesc('pelanggaran_count')->take(5)->get();

        return view('user.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalKelas' => $totalKelas,
            'totalPelanggaran' => $totalPelanggaran,
            'pelanggaranPerBulanChart' => ['labels' => $labels, 'data' => $data],
            'topSiswa' => [
                'labels' => $topSiswaData->pluck('siswa.nama_siswa'),
                'data' => $topSiswaData->pluck('total'),
            ],
            'topKelas' => [
                'labels' => $topKelasData->pluck('kode_kelas'),
                'data' => $topKelasData->pluck('total'),
            ],
            'topJenisPelanggaran' => $topJenisPelanggaran,
            'tahunAktif' => $tahunAktif,
        ]);
    }

    public function getChartData()
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAktif) {
            return response()->json([]);
        }

        $data = Pelanggaran::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }
}
