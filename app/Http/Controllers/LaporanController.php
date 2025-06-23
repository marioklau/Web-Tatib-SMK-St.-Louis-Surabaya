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

        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();
        $selectedKelas = $request->get('kelas_id');

        $siswa = Siswa::with(['kelas', 'pelanggaran.kategori'])
        ->withCount('pelanggaran')
        ->when($selectedKelas, function ($query, $kelas_id) {
            return $query->where('kelas_id', $kelas_id);
        })
        ->whereHas('pelanggaran')
        ->get();

        return view('laporan.index', compact('siswa', 'kelasList', 'selectedKelas'));
    }

    public function exportPdf(Request $request)
    {
        $kelas = Kelas::find($request->kelas_id);
        $siswa = Siswa::where('kelas_id', $request->kelas_id)->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('siswa', 'kelas'));
        return $pdf->download('laporan-siswa.pdf');
    }
}

