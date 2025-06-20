@extends('layouts.main')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold text-start">Tahun Ajaran</h1>

    <!-- Tombol Tambah Kategori -->
    <div class="flex flex-col md:flex-row justify-end items-center mb-6">
        <a href="{{ route('tahun-ajaran.create') }}">
            <button type="button" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path>
                </svg>
                Tambah Tahun Ajaran
            </button>
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-300 text-gray-900 uppercase text-sm leading-normal">
                    <th class="py-1 px-3 border text-left">No</th>
                    <th class="py-1 px-3 border text-left">Tahun Ajaran</th>
                    <th class="py-1 px-3 border text-left">Status</th>
                    <th class="py-1 px-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-900 text-sm font-light">
            </tbody>
        </table>
    </div>
</div>
@endsection