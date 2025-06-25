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

        // Ambil hanya kelas yang memiliki siswa dengan pelanggaran
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAktif->id)
            ->whereHas('siswa.pelanggaran', function ($query) use ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif->id);
            })
            ->get();

        // Ambil siswa dengan pelanggaran saja
        $siswa = Siswa::with(['kelas', 'pelanggaran.kategori'])
            ->withCount(['pelanggaran' => function ($query) use ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif->id);
            }])
            ->whereHas('pelanggaran', function ($query) use ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif->id);
            })
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

        // Ambil siswa dengan pelanggaran saja
        $query = Siswa::with(['kelas', 'pelanggaran.kategori'])
            ->withCount(['pelanggaran' => function ($query) use ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif->id);
            }])
            ->whereHas('pelanggaran', function ($query) use ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif->id);
            });

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
