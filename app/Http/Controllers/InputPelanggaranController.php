<?php

namespace App\Http\Controllers;

use App\Models\KeputusanTindakan;
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

        $pelanggaran = Pelanggaran::with('siswa.kelas', 'kategori', 'jenis', 'sanksi', 'keputusanTindakan')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->latest()
            ->get();

        $siswa = Siswa::with('kelas')
        ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
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
            }
        ])
        ->get();

        return view('input_pelanggaran.index', compact('pelanggaran', 'siswa'));}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        $siswa = Siswa::with('kelas')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
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
                }
            ])
            ->get();

        $jenis = Jenis::with('kategori')->get();
        $sanksi = Sanksi::all();
        $tahun_ajaran = $tahunAjaranAktif;
        $keputusanTindakan = KeputusanTindakan::all(); // tambahkan ini

        return view('input_pelanggaran.create', compact('siswa', 'jenis', 'sanksi', 'tahun_ajaran', 'keputusanTindakan'));
    }


    /**
     * Store a newly created resource in storage.
     */
   
        
        public function store(Request $request)
        {

            $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();

            if (!$tahunAjaranAktif) {
                return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
            }

            $validatedData = $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'jenis_id' => 'required|exists:jenis,id',
                'kategori_id' => 'required|exists:kategori,id',
                'sanksi_id' => 'required|exists:sanksi,id',
                'keputusan_tindakan_id' => 'required|exists:keputusan_tindakan,id', // Pastikan ini ada
            ]);
            // Ambil sanksi yang dipilih
            $sanksi = Sanksi::findOrFail($validatedData['sanksi_id']);
            $keputusanTindakan = $sanksi->keputusan_tindakan; 
     
            
            // Ambil keputusan tindakan yang dipilih
            $keputusanTindakan = KeputusanTindakan::findOrFail($validatedData['keputusan_tindakan_id']);
            Pelanggaran::create([
                'siswa_id' => $validatedData['siswa_id'],
                'kategori_id' => $validatedData['kategori_id'],
                'jenis_id' => $validatedData['jenis_id'],
                'sanksi_id' => $validatedData['sanksi_id'],
                'keputusan_tindakan' => $keputusanTindakan->nama_keputusan, // Simpan hanya satu keputusan tindakan
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                'status' => 'Belum',
            ]);
            return redirect()->route('input_pelanggaran.index')->with('success', 'Pelanggaran berhasil ditambahkan.');
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pelanggaran = Pelanggaran::with(['siswa.kelas', 'jenis.kategori', 'sanksi', 'tahunAjaran'])->findOrFail($id);
        return view('input_pelanggaran.show', compact('pelanggaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pelanggaran = Pelanggaran::with('siswa', 'kategori', 'jenis', 'sanksi')->findOrFail($id);
        $siswa = Siswa::with('kelas')->get();
        $jenis = Jenis::with('kategori')->get();
        $sanksi = Sanksi::all();
        $tahun_ajaran = Tahun::where('status', 'aktif')->first();

        return view('input_pelanggaran.edit', compact('pelanggaran', 'siswa', 'jenis', 'sanksi', 'tahun_ajaran'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        $pelanggaran = Pelanggaran::findOrFail($id);

        $validatedData = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_id' => 'required|exists:jenis,id',
            'kategori_id' => 'required|exists:kategori,id',
            'sanksi_id' => 'required|exists:sanksi,id', // Pastikan sanksi_id divalidasi
        ]);

        $pelanggaran->update([
            'siswa_id' => $validatedData['siswa_id'],
            'kategori_id' => $validatedData['kategori_id'],
            'jenis_id' => $validatedData['jenis_id'],
            'sanksi_id' => $validatedData['sanksi_id'], // Perbarui sanksi_id
            'tahun_ajaran_id' => $tahunAjaranAktif->id,
        ]);

        return redirect()->route('input_pelanggaran.index')->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $request->validate(['status' => 'required|in:Belum,Sudah']);

        $pelanggaran->status = $request->status;
        $pelanggaran->save();

        return response()->json(['message' => 'Status updated successfully', 'status' => $pelanggaran->status]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->delete();

        return redirect()->route('input_pelanggaran.index')->with('success', 'Pelanggaran berhasil dihapus.');
    }
}
