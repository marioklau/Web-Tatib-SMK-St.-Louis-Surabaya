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

        // Ambil data pelanggaran beserta relasi yang diperlukan
        // Pastikan relasi 'jenis.kategori' ada jika ingin mengakses nama kategori
        $pelanggaran = Pelanggaran::with('siswa.kelas', 'kategori', 'jenis.kategori', 'sanksi')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->latest()
            ->get();

        // Ambil data siswa dengan count pelanggaran berdasarkan kategori
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

        return view('input_pelanggaran.index', compact('pelanggaran', 'siswa'));
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
            ->get();

        $jenis = Jenis::with('kategori')->get();
        // Ambil semua sanksi. Karena kolom `nama_sanksi` dan `keputusan_tindakan` di-cast ke array di model Sanksi,
        // data ini akan otomatis menjadi array saat diambil dari database.
        $sanksi = Sanksi::all();

        return view('input_pelanggaran.create', compact('siswa', 'jenis', 'sanksi'));
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
            'kategori_id' => 'required|exists:kategori,id', // Pastikan kategori_id di-pass dari form
            'sanksi_id' => 'required|exists:sanksi,id',
            // 'keputusan_tindakan_id' => 'required|exists:keputusan_tindakan,id', // Ini akan diubah
            'keputusan_tindakan_terpilih' => 'required|string', // Menyimpan string keputusan tindakan yang dipilih
        ]);

        try {
            // Ambil bobot dari jenis pelanggaran yang dipilih
            $jenisPelanggaran = Jenis::findOrFail($validatedData['jenis_id']);
            $bobotPelanggaran = $jenisPelanggaran->bobot_poin; // Asumsikan ada kolom bobot_poin di tabel jenis

            Pelanggaran::create([
                'siswa_id' => $validatedData['siswa_id'],
                'jenis_id' => $validatedData['jenis_id'],
                'kategori_id' => $validatedData['kategori_id'],
                'sanksi_id' => $validatedData['sanksi_id'],
                'keputusan_tindakan_id' => null, // Karena kita tidak menyimpan ID, tapi string. Sesuaikan jika struktur DB berbeda.
                'keputusan_tindakan_terpilih' => $validatedData['keputusan_tindakan_terpilih'], // Simpan string yang dipilih
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                'poin_pelanggaran' => $bobotPelanggaran, // Simpan bobot poin pelanggaran
                'tanggal' => now()->toDateString(), // Atau dari input form jika ada
                'status' => 'Belum', // Default status
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
        return view('input_pelanggaran.show', ['pelanggaran' => $input_pelanggaran]);
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

        return view('input_pelanggaran.edit', compact('input_pelanggaran', 'siswa', 'jenis', 'sanksi'));
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
            'sanksi_id' => 'required|exists:sanksi,id',
            'keputusan_tindakan_terpilih' => 'required|string',
            'status' => 'required|in:Sudah,Belum', // Tambahkan validasi untuk status
        ]);

        try {
            $jenisPelanggaran = Jenis::findOrFail($validatedData['jenis_id']);
            $bobotPelanggaran = $jenisPelanggaran->bobot_poin;

            $input_pelanggaran->update([
                'siswa_id' => $validatedData['siswa_id'],
                'jenis_id' => $validatedData['jenis_id'],
                'kategori_id' => $validatedData['kategori_id'],
                'sanksi_id' => $validatedData['sanksi_id'],
                'keputusan_tindakan_terpilih' => $validatedData['keputusan_tindakan_terpilih'],
                'poin_pelanggaran' => $bobotPelanggaran,
                'status' => $validatedData['status'], // Update status
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

            return response()->json(['success' => true, 'status' => $pelanggaran->status, 'message' => 'Status berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }
    }
}