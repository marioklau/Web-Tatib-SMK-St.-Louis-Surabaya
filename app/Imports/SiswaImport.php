<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tahun;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    protected $tahunAktif;

    public function __construct()
    {
        $this->tahunAktif = Tahun::where('status', 'aktif')->first();
    }

    public function model(array $row)
    {
        if (!$this->tahunAktif) {
            return null;
        }

        $kelas = Kelas::where('kode_kelas', $row['kode_kelas'])
                      ->where('tahun_ajaran_id', $this->tahunAktif->id)
                      ->first();

        if (!$kelas) {
            return null;
        }

        return new Siswa([
            'kelas_id'        => $kelas->id,
            'nama_siswa'      => $row['nama_siswa'],
            'nis'             => $row['nis'],
            'jenis_kelamin'   => $row['jenis_kelamin'],
            'tahun_ajaran_id' => $this->tahunAktif->id, // PENTING!
        ]);
    }
}
