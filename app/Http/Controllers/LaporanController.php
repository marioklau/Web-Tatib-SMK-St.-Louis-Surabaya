<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;  // <-- import ini

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::all();
        $selectedKelas = $request->get('kelas_id');

        $siswa = Siswa::with('kelas')
            ->withCount('pelanggaran')
            ->when($selectedKelas, function ($query, $kelas_id) {
                return $query->where('kelas_id', $kelas_id);
            })
            ->get();

        return view('laporan.index', compact('siswa', 'kelasList', 'selectedKelas'));
    }

    public function exportPdf(Request $request)
    {
        $kelas = Kelas::find($request->kelas_id);
        $siswa = Siswa::where('kelas_id', $request->kelas_id)->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('siswa', 'kelas')); // <== gunakan Pdf::loadView

        return $pdf->download('laporan-siswa.pdf');
    }
}
