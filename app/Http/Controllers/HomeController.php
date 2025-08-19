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
    // Pindahkan deklarasi variabel allJenisPelanggaran ke atas
    $allJenisPelanggaran = Jenis::orderBy('bentuk_pelanggaran')->get();

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
            'allJenisPelanggaran' => $allJenisPelanggaran, // Tetap kirim data
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
        'allJenisPelanggaran' => $allJenisPelanggaran, // TAMBAHKAN INI
    ]);
}

protected function userDashboard($tahunAktif)
{
    $user = Auth::user();
    
    // Pindahkan deklarasi variabel allJenisPelanggaran ke atas
    $allJenisPelanggaran = Jenis::orderBy('bentuk_pelanggaran')->get();

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
            'allJenisPelanggaran' => $allJenisPelanggaran, // Tetap kirim data
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
        'allJenisPelanggaran' => $allJenisPelanggaran, // TAMBAHKAN INI
    ]);
}

    public function getPerbandinganData(Request $request)
{
    $tahunAktif = Tahun::where('status', 'aktif')->first();
    
    if (!$tahunAktif) {
        return response()->json([]);
    }

    $jenisId = $request->query('jenis_id');
    
    if (!$jenisId) {
        return response()->json(array_fill(0, 12, 0));
    }

    // Query data pelanggaran per bulan untuk jenis tertentu
    $dataPerBulan = Pelanggaran::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
        ->where('tahun_ajaran_id', $tahunAktif->id)
        ->where('jenis_id', $jenisId)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    $result = [];
    for ($i = 1; $i <= 12; $i++) {
        $found = $dataPerBulan->firstWhere('bulan', $i);
        $result[] = $found ? $found->total : 0;
    }

    return response()->json($result);
}


    public function getChartData(Request $request)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        
        if (!$tahunAktif) {
            return response()->json([]);
        }

        $chartType = $request->query('chart');
        $filter = $request->query('filter', 'all');
        
        // Tentukan rentang waktu berdasarkan filter
        $dateRange = $this->getDateRange($filter);
        
        // Query data berdasarkan jenis chart dan filter
        switch($chartType) {
            case 'pelanggaranChart':
                $data = $this->getPelanggaranChartData($tahunAktif->id, $dateRange);
                break;
            case 'topSiswaChart':
                $data = $this->getTopSiswaChartData($tahunAktif->id, $dateRange);
                break;
            case 'kelasChart':
                $data = $this->getTopKelasChartData($tahunAktif->id, $dateRange);
                break;
            case 'jenisChart':
                $data = $this->getTopJenisChartData($tahunAktif->id, $dateRange);
                break;
            default:
                $data = ['labels' => [], 'data' => []];
        }
        
        return response()->json($data);
    }
    
    private function getDateRange($filter)
    {
        $now = Carbon::now();
        
        switch($filter) {
            case 'daily':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'weekly':
                return [
                    'start' => $now->copy()->startOfWeek(Carbon::MONDAY),
                    'end' => $now->copy()->endOfWeek(Carbon::SUNDAY)
                ];
            case 'monthly':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            default: // all
                return [
                    'start' => null,
                    'end' => null
                ];
        }
    }
    
    private function getPelanggaranChartData($tahunId, $dateRange)
    {
        $query = Pelanggaran::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('tahun_ajaran_id', $tahunId);
            
        if ($dateRange['start'] && $dateRange['end']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }
            
        $pelanggaranPerBulan = $query->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->translatedFormat('F');
            $found = $pelanggaranPerBulan->firstWhere('bulan', $i);
            $data[] = $found ? $found->total : 0;
        }
        
        return ['labels' => $labels, 'data' => $data];
    }
    
    private function getTopSiswaChartData($tahunId, $dateRange)
    {
        $query = Pelanggaran::with('siswa')
            ->select('siswa_id', DB::raw('COUNT(*) as total'))
            ->where('tahun_ajaran_id', $tahunId);
            
        if ($dateRange['start'] && $dateRange['end']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }
            
        $topSiswaData = $query->groupBy('siswa_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return [
            'labels' => $topSiswaData->pluck('siswa.nama_siswa'),
            'data' => $topSiswaData->pluck('total')
        ];
    }
    
    private function getTopKelasChartData($tahunId, $dateRange)
    {
        $query = Pelanggaran::select('kelas.id', 'kelas.kode_kelas', DB::raw('COUNT(*) as total'))
            ->join('siswa', 'pelanggaran.siswa_id', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->where('pelanggaran.tahun_ajaran_id', $tahunId);
            
        if ($dateRange['start'] && $dateRange['end']) {
            $query->whereBetween('pelanggaran.created_at', [$dateRange['start'], $dateRange['end']]);
        }
            
        $topKelasData = $query->groupBy('kelas.id', 'kelas.kode_kelas')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return [
            'labels' => $topKelasData->pluck('kode_kelas'),
            'data' => $topKelasData->pluck('total')
        ];
    }
    
    private function getTopJenisChartData($tahunId, $dateRange)
    {
        $query = Jenis::withCount(['pelanggaran' => function ($query) use ($tahunId, $dateRange) {
            $query->where('tahun_ajaran_id', $tahunId);
            
            if ($dateRange['start'] && $dateRange['end']) {
                $query->whereBetween('pelanggaran.created_at', [$dateRange['start'], $dateRange['end']]);
            }
        }]);
            
        $topJenisPelanggaran = $query->orderByDesc('pelanggaran_count')->take(5)->get();

        return [
            'labels' => $topJenisPelanggaran->pluck('bentuk_pelanggaran'),
            'data' => $topJenisPelanggaran->pluck('pelanggaran_count')
        ];
    }
}