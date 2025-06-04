@extends('layouts.main')

@section('title', 'Input Pelanggaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-semibold mb-8">Input Pelanggaran</h1>

    <!-- Tombol Input -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('input-pelanggaran.create') }}">
        <button onclick="document.getElementById('form-section').scrollIntoView();" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Input Pelanggaran
        </button>
    </div>

    <!-- Tabel Pelanggaran -->
    <h2 class="text-xl font-semibold mb-4">Riwayat Pelanggaran</h2>
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full table-auto">
            <thead class="bg-gray-300 text-gray-900">
                <tr>
                    <th class="p-3 border text-center">Nama</th>
                    <th class="p-3 border text-center">Kelas</th>
                    <th class="p-3 border text-center">Kategori</th>
                    <th class="p-3 border text-center">Jenis</th>
                    <th class="p-3 border text-center">Sanksi</th>
                    <th class="p-3 border text-center">Waktu</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggaran as $p)
                <tr class="border-t">
                    <td class="p-3 border">{{ $p->siswa->nama_siswa }}</td>
                    <td class="p-3 border">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td class="p-3 border">{{ $p->kategori->nama_kategori }}</td>
                    <td class="p-3 border">{{ $p->jenis->bentuk_pelanggaran}}</td> 
                    <td class="p-3 border">{{ $p->sanksi->nama_sanksi }}</td>
                    <td class="p-3 border">{{ $p->created_at->format('d M Y H:i') }}</td>
                    <td class="p-3 border">
                        <div class="flex items-center space-x-2">
                            <!-- Tombol Delete -->
                            <form action="{{ route('input-pelanggaran.destroy', $p) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sanksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white flex items-center gap-1 px-3 py-1 rounded-md hover:bg-red-400 transition duration-300 text-sm" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-3 text-center">Belum ada data pelanggaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script untuk filter jenis dan sanksi -->
<script>
    const kategoriSelect = document.getElementById('kategori-select');
    const jenisSelect = document.getElementById('jenis-select');
    const sanksiSelect = document.getElementById('sanksi-select');
    const kategoriJenis = @json($kategori);

    kategoriSelect.addEventListener('change', function () {
        const kategoriId = this.value;

        // Filter jenis
        jenisSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
        kategoriJenis.forEach(kat => {
            if (kat.id == kategoriId) {
                kat.jenis.forEach(j => {
                    jenisSelect.innerHTML += `<option value="${j.id}">${j.nama_jenis}</option>`;
                });
            }
        });

        // Filter sanksi
        Array.from(sanksiSelect.options).forEach(opt => {
            if (opt.dataset.kategori && opt.dataset.kategori !== kategoriId) {
                opt.style.display = 'none';
            } else {
                opt.style.display = '';
            }
        });
    });
</script>
@endsection