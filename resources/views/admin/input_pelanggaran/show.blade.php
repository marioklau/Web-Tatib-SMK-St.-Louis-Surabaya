@extends('layouts.main')

@section('title', 'Detail Siswa')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Detail Siswa</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Siswa -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Informasi Siswa</h2>
                <div class="space-y-3">
                    <div>
                        <label class="font-medium text-gray-700">Nama:</label>
                        <p class="mt-1">{{ $siswa->nama_siswa }}</p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">NIS:</label>
                        <p class="mt-1">{{ $siswa->nis }}</p>
                    </div>
                    <div>
                        <label class="font-medium text-gray-700">Kelas:</label>
                        <p class="mt-1">{{ $siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistik Pelanggaran -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Statistik Pelanggaran</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-100 p-4 rounded-lg text-center">
                        <p class="text-sm font-medium text-green-800">Ringan (R)</p>
                        <p class="text-2xl font-bold text-green-600">{{ $siswa->ringan_count ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg text-center">
                        <p class="text-sm font-medium text-yellow-800">Berat (B)</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $siswa->berat_count ?? 0 }}</p>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg text-center">
                        <p class="text-sm font-medium text-red-800">Sangat Berat (SB)</p>
                        <p class="text-2xl font-bold text-red-600">{{ $siswa->sangat_berat_count ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg text-center">
                        <p class="text-sm font-medium text-blue-800">Total Bobot</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $siswa->pelanggaran_sum_total_bobot ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Pelanggaran -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Riwayat Pelanggaran</h2>
            @if($siswa->pelanggaran->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-left">Tanggal</th>
                                <th class="py-2 px-4 text-left">Jenis Pelanggaran</th>
                                <th class="py-2 px-4 text-left">Kategori</th>
                                <th class="py-2 px-4 text-center">Bobot</th>
                                <th class="py-2 px-4 text-left">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($siswa->pelanggaran as $pelanggaran)
                                <tr>
                                    <td class="py-2 px-4">{{ $pelanggaran->created_at->format('d/m/Y') }}</td>
                                    <td class="py-2 px-4">{{ $pelanggaran->jenis->bentuk_pelanggaran }}</td>
                                    <td class="py-2 px-4">{{ $pelanggaran->jenis->kategori->nama_kategori }}</td>
                                    <td class="py-2 px-4 text-center">{{ $pelanggaran->total_bobot }}</td>
                                    <td class="py-2 px-4">{{ $pelanggaran->keputusan_tindakan_terpilih }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Siswa ini belum memiliki riwayat pelanggaran.</p>
            @endif
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('siswa.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Kembali</a>
        </div>
    </div>
</div>
@endsection