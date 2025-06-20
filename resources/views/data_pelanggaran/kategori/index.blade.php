@extends('layouts.main')

@section('title', 'Kategori Pelanggaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold text-start mb-2">Kategori Pelanggaran</h1>

    <!-- Tombol Tambah Kategori -->
    <div class="flex flex-col md:flex-row justify-end items-center mb-6">
        <a href="{{ route('kategori.create') }}">
            <button type="button" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path>
                </svg>
                Tambah Kategori
            </button>
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-300 text-gray-900 uppercase text-sm leading-normal">
                    <th class="py-1 px-2 border text-center">No</th>
                    <th class="py-1 px-2 border text-left">Kategori Pelanggaran</th>
                    <th class="py-1 px-2 border text-center">Jumlah</th>
                    <th class="py-1 px-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse ($kategori as $category)
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="py-1 px-2 border text-center">{{ $loop->iteration }}</td>
                        <td class="py-1 px-2 border text-left">{{ $category->nama_kategori }}</td>
                        <td class="py-1 px-2 border text-center">
                            {{-- Ganti ini jika Anda memiliki data jumlah pelanggaran --}}
                            {{ $category-> jenis_count }}
                        </td>
                        <td class="py-1 px-2 text-center">
                            <div class="flex items-center justify-center space-x-2                                <a href="{{ route('kategori.edit', $category) }}" class="text-blue-600 hover:text-blue-800 transform hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <!-- Tombol Delete -->
                                <form action="{{ route('kategori.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transform hover:scale-110">
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
                    <tr>
                        <td colspan="4" class="py-3 px-6 text-center text-gray-500">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection