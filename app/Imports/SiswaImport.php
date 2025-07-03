<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tahun;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;

class SiswaImport implements ToModel, WithHeadingRow
{
    protected $tahunAktif;

    public function __construct()
    {
        $this->tahunAktif = Tahun::where('status', 'aktif')->first();
    }

    public function model(array $row)
    {
        $tahunAktif = Tahun::where('status', 'aktif')->first();
        if (!$tahunAktif) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Tidak ada tahun ajaran aktif.',
            ]);
        }

        $kelas = Kelas::where('kode_kelas', $row['kode_kelas'])
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->first();

        if (!$kelas) {
            throw ValidationException::withMessages([
                'kelas' => 'Kelas dengan kode ' . $row['kode_kelas'] . ' tidak ditemukan pada tahun ajaran aktif.',
            ]);
        }

        $nisSudahAda = Siswa::where('nis', $row['nis'])
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->exists();

        if ($nisSudahAda) {
            throw ValidationException::withMessages([
                'nis' => 'NIS ' . $row['nis'] . ' sudah ada di tahun ajaran aktif.',
            ]);
        }

        return new Siswa([
            'nama_siswa' => $row['nama_siswa'],
            'nis' => $row['nis'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'kelas_id' => $kelas->id,
            'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
        ]);
    }

}
