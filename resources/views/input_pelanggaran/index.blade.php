@extends('layouts.main')

@section('title', 'Input Pelanggaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Input Pelanggaran</h1>

    <!-- Tombol Input -->
    <div class="flex justify-end mb-4">
        <button onclick="document.getElementById('form-section').scrollIntoView();" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Input Pelanggaran
        </button>
    </div>

    <!-- Form Input -->
    <form method="POST" action="{{ route('input-pelanggaran.store') }}" class="bg-white rounded shadow p-6 mb-10" id="form-section">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1">Nama Siswa</label>
                <select name="siswa_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border p-2 rounded" required id="kategori-select">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                <select name="jenis_id" class="w-full border p-2 rounded" required id="jenis-select">
                    <option value="">-- Pilih Jenis --</option>
                </select>
            </div>

            <div>
                <label class="block mb-1">Sanksi</label>
                <select name="sanksi_id" class="w-full border p-2 rounded" required id="sanksi-select">
                    <option value="">-- Pilih Sanksi --</option>
                    @foreach($sanksi as $s)
                        <option data-kategori="{{ $s->kategori_id }}" value="{{ $s->id }}">{{ $s->nama_sanksi }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>

    <!-- Tabel Pelanggaran -->
    <h2 class="text-xl font-semibold mb-4">Riwayat Pelanggaran</h2>
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full table-auto">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Kelas</th>
                    <th class="p-3 text-left">Kategori</th>
                    <th class="p-3 text-left">Jenis</th>
                    <th class="p-3 text-left">Sanksi</th>
                    <th class="p-3 text-left">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggaran as $p)
                <tr class="border-t">
                    <td class="p-3">{{ $p->siswa->nama_siswa }}</td>
                    <td class="p-3">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td class="p-3">{{ $p->kategori->nama_kategori }}</td>
                    <td class="p-3">{{ $p->jenis->nama_jenis }}</td>
                    <td class="p-3">{{ $p->sanksi->nama_sanksi }}</td>
                    <td class="p-3">{{ $p->created_at->format('d M Y H:i') }}</td>
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