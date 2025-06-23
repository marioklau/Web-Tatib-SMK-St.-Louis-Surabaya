@extends('layouts.main')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold text-start">Tahun Ajaran</h1>

    <!-- Tombol Tambah Kategori -->
    <div class="flex flex-col md:flex-row justify-end items-center mb-3">
        <a href="{{ route('tahun-ajaran.create') }}">
            <button type="button" class="flex items-center bg-green-600 text-white px-2 py-1 hover:bg-green-700 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="18" height="18" viewBox="0 0 22 22" fill="#ffffff">
                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path>
                </svg>
                Tambah
            </button>
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="overflow-hidden">
        <table class="min-w-full rounded-xl">
            <thead>
                <tr class="bg-gray-200">
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">No</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Tahun Ajaran</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Status</th>
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @foreach ($tahunAjaran as $i => $t)
                    <tr>
                        <td class="p-1">{{ $i + 1 }}</td>
                        <td class="p-1">{{ $t->tahun_ajaran }}</td>
                        <td class="p-1">
                            @if ($t->status == 'aktif')
                                <span class="text-green-600 font-semibold">Aktif</span>
                            @else
                                <span class="text-gray-500">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-1 text-center">
                            <form action="{{ route('tahun-ajaran.aktifkan', $t->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="bg-blue-500 text-white px-2 py-1 text-sm rounded hover:bg-blue-600">Aktifkan</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection