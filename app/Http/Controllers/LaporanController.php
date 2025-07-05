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

        // Definisikan filter pelanggaran di sini agar bisa dipakai ulang
        $pelanggaranFilter = function ($query) use ($tahunAktif) {
            $query->where('tahun_ajaran_id', $tahunAktif->id)
                  ->where(function ($subQuery) {
                      $subQuery->where('status', 'Belum')
                               ->orWhereDate('created_at', now()->toDateString());
                  });
        };

        // Ambil hanya kelas yang memiliki siswa dengan pelanggaran (yang sudah difilter)
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAktif->id)
            ->whereHas('siswa.pelanggaran', $pelanggaranFilter) // Gunakan filter
            ->get();

        // Ambil siswa dengan pelanggaran (yang sudah difilter)
        $siswa = Siswa::with(['kelas', 'pelanggaran' => $pelanggaranFilter, 'pelanggaran.kategori']) // Gunakan filter pada relasi
            ->withCount(['pelanggaran' => $pelanggaranFilter]) // Gunakan filter pada count
            ->whereHas('pelanggaran', $pelanggaranFilter) // Gunakan filter pada pengecekan
            ->when($selectedKelas, function ($query, $kelas_id) {
                return $query->where('kelas_id', $kelas_id);
            })
            ->get();

        return view('laporan.index', compact('siswa', 'kelasList', 'selectedKelas'));
    }

    public function exportPdf(Request $request)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        $kelasId = $request->get('kelas_id');

        // Definisikan filter pelanggaran yang sama untuk konsistensi data di PDF
        $pelanggaranFilter = function ($query) use ($tahunAktif) {
            $query->where('tahun_ajaran_id', $tahunAktif->id)
                  ->where(function ($subQuery) {
                      $subQuery->where('status', 'Belum')
                               ->orWhereDate('created_at', now()->toDateString());
                  });
        };

        // Ambil siswa dengan pelanggaran (yang sudah difilter)
        $query = Siswa::with(['kelas', 'pelanggaran' => $pelanggaranFilter, 'pelanggaran.kategori']) // Gunakan filter
            ->withCount(['pelanggaran' => $pelanggaranFilter]) // Gunakan filter
            ->whereHas('pelanggaran', $pelanggaranFilter); // Gunakan filter

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
            $kelas = Kelas::find($kelasId);
        } else {
            $kelas = null;
        }

        $siswa = $query->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('siswa', 'kelas'));
        return $pdf->download('laporan-siswa.pdf');
    }
}