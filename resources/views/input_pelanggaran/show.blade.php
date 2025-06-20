@extends('layouts.main')

@section('title', 'Detail Pelanggaran')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-semibold mb-4">Detail Pelanggaran</h1>

    <div class="bg-white rounded shadow p-6">
        <table class="table-auto w-full text-sm">
            <tr><td class="font-semibold w-1/3">Nama Siswa</td><td>{{ $pelanggaran->siswa->nama_siswa }}</td></tr>
            <tr><td class="font-semibold">Kelas</td><td>{{ $pelanggaran->siswa->kelas->nama_kelas ?? '-' }}</td></tr>
            <tr><td class="font-semibold">Kategori</td><td>{{ $pelanggaran->kategori->nama_kategori ?? '-' }}</td></tr>
            <tr><td class="font-semibold">Jenis Pelanggaran</td><td>{{ $pelanggaran->jenis->bentuk_pelanggaran }}</td></tr>
            <tr><td class="font-semibold">Sanksi</td><td>{{ $pelanggaran->sanksi->nama_sanksi }}</td></tr>
            <tr><td class="font-semibold">Status</td><td>{{ $pelanggaran->status }}</td></tr>
            <tr><td class="font-semibold">Waktu Input</td><td>{{ $pelanggaran->created_at->format('d M Y H:i') }}</td></tr>
        </table>

        <div class="mt-4">
            <a href="{{ route('input-pelanggaran.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Kembali</a>
        </div>
    </div>
</div>
@endsection