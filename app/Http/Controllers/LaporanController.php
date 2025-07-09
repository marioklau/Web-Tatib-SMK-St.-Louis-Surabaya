<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tahun;
use Barryvdh\DomPDF\Facade\Pdf;

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

        return view('laporan.index', compact(
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

        $pdf = Pdf::loadView('laporan.pdf', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'selectedPeriode' => $selectedPeriode,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return $pdf->download('laporan-pelanggaran.pdf');
    }
}