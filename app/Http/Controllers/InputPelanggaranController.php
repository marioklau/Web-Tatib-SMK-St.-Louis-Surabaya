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
use Illuminate\Validation\ValidationException; // Pastikan ini di-import

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

        // Query dasar dengan relasi dan tahun ajaran aktif
        $query = Pelanggaran::with(['siswa.kelas', 'kategori', 'jenis.kategori', 'sanksi'])
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan yang 'Belum' atau dibuat hari ini
            $query->where(function($q) {
                $q->where('status', 'Belum')
                  ->orWhereDate('created_at', now()->toDateString());
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $pelanggaran = $query->latest()->paginate(10);

        return view('admin.input_pelanggaran.index', compact('pelanggaran'));
    }
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
                        ->whereHas('jenis.kategori', fn($q) => $q->whereRaw('LOWER(nama_kategori) = ?', ['ringan']));
                },
                'pelanggaran as berat_count' => function ($query) use ($tahunAjaranAktif) {
                    $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                        ->whereHas('jenis.kategori', fn($q) => $q->whereRaw('LOWER(nama_kategori) = ?', ['berat']));
                },
                'pelanggaran as sangat_berat_count' => function ($query) use ($tahunAjaranAktif) {
                    $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                        ->whereHas('jenis.kategori', fn($q) => $q->whereRaw('LOWER(nama_kategori) = ?', ['sangat berat']));
                }
            ])
            ->withSum(['pelanggaran' => function($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
            }], 'poin_pelanggaran')
            ->get();

        $jenis = Jenis::with('kategori')->get();
        $sanksi = Sanksi::all();

        return view('admin.input_pelanggaran.create', compact('siswa', 'jenis', 'sanksi'));
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
            'keputusan_tindakan_terpilih' => 'required|string',
            // Remove sanksi_id from validation since we'll determine it automatically
        ]);

        try {
            $jenisPelanggaran = Jenis::findOrFail($validatedData['jenis_id']);
            $bobotPelanggaran = $jenisPelanggaran->bobot_poin;

            // Get the student's total bobot
            $siswa = Siswa::findOrFail($validatedData['siswa_id']);
            $totalBobot = $siswa->pelanggaran()
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->sum('poin_pelanggaran') + $bobotPelanggaran;


            // Find the appropriate sanksi based on kategori and total bobot
            $sanksi = Sanksi::where('kategori_id', $validatedData['kategori_id'])
                ->where(function($query) use ($totalBobot) {
                    $query->where('bobot_min', '<=', $totalBobot)
                        ->where('bobot_max', '>=', $totalBobot);
                })
                ->first();

            if (!$sanksi) {
                // Fallback to the first sanksi for this kategori if none matches
                $sanksi = Sanksi::where('kategori_id', $validatedData['kategori_id'])
                    ->orderBy('bobot_min')
                    ->first();
                
                if (!$sanksi) {
                    throw new \Exception('Tidak ada sanksi yang tersedia untuk kategori ini.');
                }
            }

            Pelanggaran::create([
                'siswa_id' => $validatedData['siswa_id'],
                'jenis_id' => $validatedData['jenis_id'],
                'kategori_id' => $validatedData['kategori_id'],
                'sanksi_id' => $sanksi->id,
                'keputusan_tindakan_terpilih' => $validatedData['keputusan_tindakan_terpilih'],
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                'poin_pelanggaran' => $bobotPelanggaran,
                'tanggal' => now()->toDateString(),
                'status' => 'Belum',
            ]);

            return redirect()->route('input_pelanggaran.index')->with('success', 'Pelanggaran berhasil diinput!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pelanggaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggaran $input_pelanggaran) // Laravel akan otomatis inject Pelanggaran model
    {
        // $input_pelanggaran sudah berisi data Pelanggaran yang dicari berdasarkan ID
        // Pastikan relasi sudah di-load jika ingin menampilkan data terkait
        $input_pelanggaran->load('siswa.kelas', 'kategori', 'jenis.kategori', 'sanksi');
        return view('admin.input_pelanggaran.show', ['pelanggaran' => $input_pelanggaran]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggaran $input_pelanggaran)
    {
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();
        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        $siswa = Siswa::with('kelas')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->withCount([
                'pelanggaran as ringan_count' => fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)->whereHas('jenis.kategori', fn($q) => $q->whereRaw('LOWER(nama_kategori) = ?', ['ringan'])),
                'pelanggaran as berat_count' => fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)->whereHas('jenis.kategori', fn($q) => $q->whereRaw('LOWER(nama_kategori) = ?', ['berat'])),
                'pelanggaran as sangat_berat_count' => fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)->whereHas('jenis.kategori', fn($q) => $q->whereRaw('LOWER(nama_kategori) = ?', ['sangat berat'])),
            ])
            ->get();

        $jenis = Jenis::with('kategori')->get();
        $sanksi = Sanksi::all();

        return view('admin.input_pelanggaran.edit', compact('input_pelanggaran', 'siswa', 'jenis', 'sanksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggaran $input_pelanggaran)
    {
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        $validatedData = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_id' => 'required|exists:jenis,id',
            'kategori_id' => 'required|exists:kategori,id',
            'keputusan_tindakan_terpilih' => 'required|string',
            'status' => 'required|in:Sudah,Belum',
        ]);

        try {
            $jenisPelanggaran = Jenis::findOrFail($validatedData['jenis_id']);
            $bobotPelanggaran = $jenisPelanggaran->bobot_poin;

            // Get the student's total bobot (excluding current pelanggaran)
            $totalBobot = Pelanggaran::where('siswa_id', $validatedData['siswa_id'])
                ->where('id', '!=', $input_pelanggaran->id)
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->sum('poin_pelanggaran') + $bobotPelanggaran;


            // Find appropriate sanksi
            $sanksi = Sanksi::where('kategori_id', $validatedData['kategori_id'])
                ->where(function($query) use ($totalBobot) {
                    $query->where('bobot_min', '<=', $totalBobot)
                        ->where('bobot_max', '>=', $totalBobot);
                })
                ->first();

            if (!$sanksi) {
                $sanksi = Sanksi::where('kategori_id', $validatedData['kategori_id'])
                    ->orderBy('bobot_min')
                    ->first();
                
                if (!$sanksi) {
                    throw new \Exception('Tidak ada sanksi yang tersedia untuk kategori ini.');
                }
            }

            $input_pelanggaran->update([
                'siswa_id' => $validatedData['siswa_id'],
                'jenis_id' => $validatedData['jenis_id'],
                'kategori_id' => $validatedData['kategori_id'],
                'sanksi_id' => $sanksi->id,
                'keputusan_tindakan_terpilih' => $validatedData['keputusan_tindakan_terpilih'],
                'poin_pelanggaran' => $bobotPelanggaran,
                'status' => $validatedData['status'],
            ]);

            return redirect()->route('input_pelanggaran.index')->with('success', 'Pelanggaran berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pelanggaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggaran $input_pelanggaran)
    {
        try {
            $input_pelanggaran->delete();
            return redirect()->route('input_pelanggaran.index')->with('success', 'Pelanggaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pelanggaran: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Sudah,Belum',
        ]);

        try {
            $pelanggaran = Pelanggaran::findOrFail($id);
            $pelanggaran->status = $request->status;
            $pelanggaran->save();

            return response()->json([
                'success' => true, 
                'status' => $pelanggaran->status, 
                'message' => 'Status berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pelanggaranSiswa(Request $request)
    {
        $tahunAjaranAktif = Tahun::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        // Query dasar dengan relasi dan tahun ajaran aktif
        $query = Pelanggaran::with(['siswa.kelas', 'kategori', 'jenis.kategori', 'sanksi'])
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan yang 'Belum' atau dibuat hari ini
            $query->where(function($q) {
                $q->where('status', 'Belum')
                  ->orWhereDate('created_at', now()->toDateString());
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $pelanggaran = $query->latest()->paginate(10);

        return view('user.pelanggaran_siswa', compact('pelanggaran'));
    }
}