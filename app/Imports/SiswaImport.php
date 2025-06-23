<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Tahun;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    // Cari kelas berdasarkan kode_kelas
    $kelas = Kelas::where('kode_kelas', $row['kode_kelas'])->first();

    // Ambil tahun ajaran aktif
    $tahunAktif = Tahun::where('status', 'aktif')->first();

    if (!$kelas || !$tahunAktif) {
        return null; // bisa lempar exception juga jika perlu
    }

    return new Siswa([
        'kelas_id'         => $kelas->id,
        'nama_siswa'       => $row['nama_siswa'],
        'jenis_kelamin'    => $row['jenis_kelamin'],
        'tahun_ajaran_id'  => $tahunAktif->id,
    ]);
}

}