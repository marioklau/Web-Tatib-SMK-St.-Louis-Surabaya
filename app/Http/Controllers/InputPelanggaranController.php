<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggaran; 
use App\Models\Kategori;
use App\Models\Jenis;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sanksi;
use App\Models\Tahun;

class InputPelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        $pelanggaran = Pelanggaran::with('siswa.kelas', 'kategori', 'jenis', 'sanksi')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->latest()
            ->get();

        $siswa = Siswa::with('kelas')
        ->where('tahun_ajaran_id', $tahunAjaranAktif->id) // ✅ tambahkan ini
        ->withCount([
            'pelanggaran as ringan_count' => function ($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->whereHas('jenis.kategori', function ($q) {
                        $q->whereRaw('LOWER(nama_kategori) = ?', ['ringan']);
                    });
            },
            'pelanggaran as berat_count' => function ($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->whereHas('jenis.kategori', function ($q) {
                        $q->whereRaw('LOWER(nama_kategori) = ?', ['berat']);
                    });
            },
            'pelanggaran as sangat_berat_count' => function ($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->whereHas('jenis.kategori', function ($q) {
                        $q->whereRaw('LOWER(nama_kategori) = ?', ['sangat berat']);
                    });
            },
        ])
        ->get();


        $kategori = Kategori::with('jenis')->get();
        $sanksi = Sanksi::all();

        return view('input_pelanggaran.index', compact(
            'pelanggaran',
            'siswa',
            'kategori',
            'sanksi',
            'tahunAjaranAktif'
        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswa = Siswa::with('kelas')->get();
        $jenis = Jenis::with('kategori')->get();
        $sanksi = Sanksi::all();

        return view('input_pelanggaran.create', compact('siswa', 'jenis', 'sanksi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kategori_id' => 'exists:kategori,id',
            'jenis_id' => 'required|exists:jenis,id',
            'sanksi_id' => 'required|exists:sanksi,id',
        ]);

        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        // Simpan data pelanggaran
        Pelanggaran::create([
            'siswa_id' => $request->siswa_id,
            'kategori_id' => $request->kategori_id,
            'jenis_id' => $request->jenis_id,
            'sanksi_id' => $request->sanksi_id,
            'tahun_ajaran_id' => $tahunAjaranAktif->id,
            'status' => 'Belum',
        ]);

        return redirect()->route('input-pelanggaran.index')->with('success', 'Pelanggaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pelanggaran = Pelanggaran::with('siswa.kelas', 'kategori', 'jenis', 'sanksi')->findOrFail($id);
        return view('input_pelanggaran.show', compact('pelanggaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $siswa = Siswa::with('kelas')->get();
        $kategori = Kategori::with('jenis')->get();
        $jenis = Jenis::all();
        $sanksi = Sanksi::all();

        return view('input_pelanggaran.edit', compact(
            'pelanggaran',
            'siswa',
            'kategori',
            'jenis',
            'sanksi'
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kategori_id' => 'exists:kategori,id',
            'jenis_id' => 'required|exists:jenis,id',
            'sanksi_id' => 'required|exists:sanksi,id',
            'status' => 'required|in:Sudah,Belum',
        ]);

        $pelanggaran = Pelanggaran::findOrFail($id); // Find the existing record

        // Ambil tahun ajaran aktif (only if you need to update it, otherwise remove)
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();
        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        $pelanggaran->update([ // Update the existing record
            'siswa_id' => $request->siswa_id,
            'kategori_id' => $request->kategori_id,
            'jenis_id' => $request->jenis_id,
            'sanksi_id' => $request->sanksi_id,
            'tahun_ajaran_id' => $tahunAjaranAktif->id, // Update if needed
            'status' => $request->status,
        ]);

        return redirect()->route('input-pelanggaran.index')->with('success', 'Pelanggaran berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->delete();

        return redirect()->route('input-pelanggaran.index')
                ->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    /**
     * Update status pelanggaran (AJAX).
     */
    public function updateStatus(Request $request, Pelanggaran $offense)
    {
        $request->validate([
            'status' => ['required', 'in:Sudah,Belum'],
        ]);

        try {
            $offense->status = $request->input('status');
            $offense->save();

            return response()->json([
                'message' => 'Status updated successfully',
                'status' => $offense->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
