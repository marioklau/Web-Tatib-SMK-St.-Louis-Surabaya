@extends('layouts.main')

@section('title', 'Detail Pelanggaran')

@section('content')
<div class="container mx-auto mt-10 mb-10 px-10">
    <h1 class="text-3xl font-bold mb-6">Detail Pelanggaran</h1>

    <div class="bg-white p-6 rounded shadow-sm">
        <table class="w-full text-sm text-left text-gray-700">
            <tbody>
                <tr class="border-b">
                    <th class="py-2 px-4 font-medium text-gray-900">Nama Siswa</th>
                    <td class="py-2 px-4">{{ $pelanggaran->siswa->nama }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 font-medium text-gray-900">Kategori</th>
                    <td class="py-2 px-4">{{ $pelanggaran->kategori->nama_kategori }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 font-medium text-gray-900">Jenis</th>
                    <td class="py-2 px-4">{{ $pelanggaran->jenis->nama_jenis }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 font-medium text-gray-900">Sanksi</th>
                    <td class="py-2 px-4">{{ $pelanggaran->sanksi->nama_sanksi }}</td>
                </tr>
                <tr>
                    <th class="py-2 px-4 font-medium text-gray-900">Tanggal Input</th>
                    <td class="py-2 px-4">{{ $pelanggaran->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-5 flex space-x-3">
        <a href="{{ route('pelanggaran.index') }}"
           class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs uppercase rounded-full hover:bg-gray-300 transition">
            Kembali
        </a>

        <a href="{{ route('pelanggaran.edit', $pelanggaran->id) }}"
           class="px-6 py-2.5 bg-blue-400 text-white font-medium text-xs uppercase rounded-full hover:bg-blue-500 transition">
            Edit
        </a>
    </div>
</div>
@endsection