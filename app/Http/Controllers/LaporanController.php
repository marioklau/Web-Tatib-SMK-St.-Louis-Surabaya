<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tahun;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        $selectedKelas = $request->get('kelas_id');
        $selectedPeriode = $request->get('periode', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Definisikan filter pelanggaran di sini agar bisa dipakai ulang
        $pelanggaranFilter = function ($query) use ($tahunAktif, $selectedPeriode, $startDate, $endDate) {
            $query->where('tahun_ajaran_id', $tahunAktif->id);
            
            // Tambahkan filter berdasarkan periode
            switch ($selectedPeriode) {
                case 'hari':
                    $query->whereDate('created_at', today());
                    break;
                case 'minggu':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'bulan':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
                case 'tahun':
                    $query->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                    break;
                default:
                    // Untuk 'all' tidak perlu filter tambahan
                    break;
            }
            
            $query->where(function ($subQuery) {
                $subQuery->where('status', 'Belum')
                        ->orWhereDate('created_at', now()->toDateString());
            });
        };

        // Ambil hanya kelas yang memiliki siswa dengan pelanggaran (yang sudah difilter)
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAktif->id)
            ->whereHas('siswa.pelanggaran', $pelanggaranFilter)
            ->get();

        // Ambil siswa dengan pelanggaran (yang sudah difilter)
        $siswa = Siswa::with(['kelas', 'pelanggaran' => $pelanggaranFilter, 'pelanggaran.kategori'])
            ->withCount(['pelanggaran' => $pelanggaranFilter])
            ->whereHas('pelanggaran', $pelanggaranFilter)
            ->when($selectedKelas, function ($query, $kelas_id) {
                return $query->where('kelas_id', $kelas_id);
            })
            ->get();

        return view('admin.laporan.index', compact(
            'siswa', 
            'kelasList', 
            'selectedKelas',
            'selectedPeriode',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        $kelasId = $request->get('kelas_id');
        $selectedPeriode = $request->get('periode', 'all'); // Pastikan nama parameter sesuai
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Definisikan filter pelanggaran
        $pelanggaranFilter = function ($query) use ($tahunAktif, $selectedPeriode, $startDate, $endDate) {
            $query->where('tahun_ajaran_id', $tahunAktif->id);
            
            // Filter berdasarkan periode
            switch ($selectedPeriode) {
                case 'hari':
                    $query->whereDate('created_at', today());
                    break;
                case 'minggu':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'bulan':
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
                case 'tahun':
                    $query->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                    break;
            }
            
            $query->where(function ($subQuery) {
                $subQuery->where('status', 'Belum')
                        ->orWhereDate('created_at', now()->toDateString());
            });
        };

        // Query data
        $query = Siswa::with(['kelas', 'pelanggaran' => $pelanggaranFilter, 'pelanggaran.kategori'])
                ->whereHas('pelanggaran', $pelanggaranFilter);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
            $kelas = Kelas::find($kelasId);
        } else {
            $kelas = null;
        }

        $siswa = $query->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'selectedPeriode' => $selectedPeriode,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('admin.laporan-pelanggaran.pdf');
    }

    public function exportExcel(Request $request)
    {
        $kelasId = $request->kelas_id;
        $periode = $request->periode;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Ambil data siswa + relasi pelanggaran
        $siswa = Siswa::with(['kelas', 'pelanggaran.jenis', 'pelanggaran.kategori'])
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->get();

        $kelasName = null;
        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            $kelasName = $kelas ? $kelas->nama_kelas : null;
        }

        // Logika olah data (menghitung jumlah tiap jenis)
        $rows = $siswa->map(function ($s, $i) {
            $pel = $s->pelanggaran;

            $rambut = $pel->filter(fn($p) => Str::contains(strtolower($p->jenis->bentuk_pelanggaran), 'rambut'))->count();
            $seragam = $pel->filter(fn($p) => Str::contains(strtolower($p->jenis->bentuk_pelanggaran), 'sabuk'))->count();
            $sepatu  = $pel->filter(fn($p) => Str::contains(strtolower($p->jenis->bentuk_pelanggaran), 'sepatu'))->count();
            $petisi  = $pel->filter(fn($p) => Str::contains(strtolower($p->jenis->bentuk_pelanggaran), 'bicara kotor'))->count();

            $terlambat = $pel->filter(fn($p) => Str::contains(strtolower($p->jenis->bentuk_pelanggaran), 'terlambat'))->count();
            $berat     = $pel->where('kategori.nama_kategori', 'BERAT')->count();
            $sangat    = $pel->where('kategori.nama_kategori', 'SANGAT BERAT')->count();

            return [
                'no'       => $i + 1,
                'nama'     => $s->nama_siswa,
                'rambut'   => $rambut,
                'seragam'  => $seragam,
                'sepatu'   => $sepatu,
                'petisi'   => $petisi,
                'terlambat'=> $terlambat,
                'berat'    => $berat,
                'sangat'   => $sangat,
                'totalR'   => $rambut + $seragam + $sepatu + $petisi,
                'totalT'   => $terlambat,
                'totalB'   => $berat,
                'totalSB'  => $sangat,
            ];
        });
        
        // Export
        return Excel::download(new class($rows, $kelasName) implements FromView, ShouldAutoSize {
            private $rows;
            private $kelasName;

            public function __construct($rows, $kelasName) { 
                $this->rows = $rows;
                $this->kelasName = $kelasName;
            }

            public function view(): \Illuminate\Contracts\View\View {
                return view('admin.laporan.excel', [
                    'rows' => $this->rows,
                    'kelasName' => $this->kelasName
                ]);
            }
        }, 'laporan_siswa.xlsx');
    }
}