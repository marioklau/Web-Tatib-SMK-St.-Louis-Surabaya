<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari kelas berdasarkan kode_kelas
        $kelas = Kelas::where('kode_kelas', $row['kode_kelas'])->first();

        // Pastikan kelas ditemukan, jika tidak bisa dilewati atau ditangani error
        if (!$kelas) {
            return null; // atau bisa lempar exception jika ingin error
        }

        return new Siswa([
            'kelas_id' => $kelas->id,
            'nama_siswa' => $row['nama_siswa'],
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }
}