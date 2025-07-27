<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tahun;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KelasController extends Controller
{
    public function index()
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        if (!$tahunAktif) {
            $kelas = collect(); // supaya tidak error di view saat foreach
            // Jika user biasa (bukan admin), tidak boleh lanjut
        if (auth()->user()->role === 'user') {
            return redirect()->back()->with('error', 'Tahun ajaran aktif belum diatur.');
        }

        // Jika admin, tetap bisa masuk tapi tanpa data
        $kelas = collect(); // Supaya tidak error di view saat foreach

        return view('admin.kelas.index', compact('kelas'));
            
        }

        $kelas = Kelas::withCount('siswa')
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->latest()
            ->paginate(10);

        // return view('admin.kelas.index', compact('kelas'));
        if (auth()->user()->role === 'admin') {
            return view('admin.kelas.index', compact('kelas'));
        } else {
            return view('user.data_kelas', compact('kelas'));
        }
    }


    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
{
    $tahunAktif = Tahun::where('status', 'aktif')->first();
    if (!$tahunAktif) {
        return back()->with('error', 'Tidak ada tahun ajaran aktif.');
    }

    $request->validate([
        'kode_kelas' => [
            'required',
            function ($attribute, $value, $fail) use ($tahunAktif) {
                if (Kelas::where('kode_kelas', $value)
                        ->where('tahun_ajaran_id', $tahunAktif->id)
                        ->exists()) {
                    $fail('Kode kelas sudah digunakan di tahun ajaran ini.');
                }
            }
        ],
        'nama_kelas' => 'required',
        'wali_kelas' => 'required'
    ]);

    Kelas::create([
        'kode_kelas' => $request->kode_kelas,
        'nama_kelas' => $request->nama_kelas,
        'wali_kelas' => $request->wali_kelas,
        'tahun_ajaran_id' => $tahunAktif->id
    ]);

    return redirect()->route('kelas.index')
        ->with('success', 'Kelas Berhasil Ditambahkan.');
}

    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
{
    $request->validate([
        'kode_kelas' => [
            'required',
            function ($attribute, $value, $fail) use ($kelas) {
                if (Kelas::where('kode_kelas', $value)
                        ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                        ->where('id', '!=', $kelas->id)
                        ->exists()) {
                    $fail('Kode kelas sudah digunakan di tahun ajaran ini.');
                }
            }
        ],
        'nama_kelas' => 'required',
        'wali_kelas' => 'required'
    ]);

    $kelas->update([
        'kode_kelas' => $request->kode_kelas,
        'nama_kelas' => $request->nama_kelas,
        'wali_kelas' => $request->wali_kelas
    ]);

    return redirect()->route('kelas.index')
        ->with('success', 'Kelas Berhasil Diupdate.');
}


    public function destroy(Kelas $kelas)
    {
        try {
            $kelas->delete();
            return redirect()->route('kelas.index')
                ->with('success', 'Kelas Berhasil Dihapus');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1451) {
                return redirect()->back()
                    ->with('error', 'Kelas tidak dapat dihapus karena sudah terdapat pelanggaran yang terkait.');
            }
        }
    }
}