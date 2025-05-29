@extends('layouts.main')

@section('title', 'Siswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-start mb-8">Data Siswa</h1>

    <form method="GET" action="{{ route('siswa.index') }}" class="mb-4">
    <label for="kelas" class="font-medium">Filter berdasarkan Kelas:</label>
    <select name="kelas_id" id="kelas" onchange="this.form.submit()" class="border rounded px-2 py-1 ml-2">
        <option value="">-- Semua Kelas --</option>
        @foreach($kelasList as $kelas)
            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->nama_kelas }}
            </option>
        @endforeach
    </select>
</form>
    
    <div class="flex flex-col md:flex-row justify-end items-center mb-6">
        <!-- Tombol Tambah -->
        <a href="{{ route('siswa.create') }}">
            <button type="button" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path>
                </svg>
                Tambah Siswa
            </button>
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">No</th>
                    <th class="py-3 px-6 text-left">Kelas</th>
                    <th class="py-3 px-6 text-left">Nama Siswa</th>
                    <th class="py-3 px-6 text-left">Jenis Kelamin</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm font-light">
                @forelse ($siswa as $student)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6 text-left">{{ $student->kelas->nama_kelas }}</td>
                        <td class="py-3 px-6 text-left">{{ $student->nama_siswa }}</td>
                        <td class="py-3 px-6 text-left">{{ $student->jenis_kelamin }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Tombol Detail -->
                                <a href="{{ route('siswa.show', $student) }}" class="bg-green-600 text-white flex items-center gap-1 px-3 py-1 rounded-md hover:bg-green-400 transition duration-300 text-sm" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M19 3h-2c0-.55-.45-1-1-1H8c-.55 0-1 .45-1 1H5c-1.1 0-2 .9-2 2v15c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2m0 17H5V5h2v2h10V5h2z" />
                                    </svg>
                                    Detail
                                </a>

                                <!-- Tombol Edit -->
                                <a href="{{ route('siswa.edit', $student) }}" class="bg-blue-600 text-white flex items-center gap-1 px-3 py-1 rounded-md hover:bg-blue-400 transition duration-300 text-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </a>

                                <!-- Tombol Delete -->
                                <form action="{{ route('siswa.destroy', $student) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?');">
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
                    <tr>
                        <td colspan="5" class="py-3 px-6 text-center text-gray-500">Belum ada siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
