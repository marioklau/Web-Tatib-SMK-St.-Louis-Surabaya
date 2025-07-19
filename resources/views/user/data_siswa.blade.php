@extends('layouts.main')

@section('title', 'Data Siswa')

@section('content')
<div class="container mx-auto" x-data="{ openDeleteModal: false, deleteUrl: '', errorMessage: '' }">
    <h1 class="text-2xl font-semibold text-start mb-4">Data Siswa</h1>

    <div class="flex flex-col gap-4 mb-2">
    <!-- Search Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
    <!-- Search Bar -->
        <form method="GET" action="{{ route('siswa.index') }}" class="relative max-w-sm w-full md:w-auto">
            <input 
                name="search" 
                value="{{ request('search') }}"
                class="w-full py-1 px-6 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                type="search" 
                placeholder="Cari Nama atau NIS">
            <button 
                type="submit"
                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.795 13.408l5.204 5.204a1 1 0 01-1.414 1.414l-5.204-5.204a7.5 7.5 0 111.414-1.414zM8.5 14A5.5 5.5 0 103 8.5 5.506 5.506 0 008.5 14z" />
                </svg>
            </button>
        </form>

        <!-- Filter Kelas -->
        <form method="GET" action="{{ route('siswa.index') }}" class="flex items-center gap-2">
            <label for="kelas" class="font-medium whitespace-nowrap">Kelas</label>
            <select name="kelas_id" id="kelas" onchange="this.form.submit()" class="border rounded px-2 py-1">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama_kelas }}
                    </option>
                @endforeach
            </select>
            <!-- Hidden input to preserve search query when filtering by class -->
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
        </form>
    </div>

    <!-- Tabel Siswa -->
    <div class="overflow-hidden">
        <table class="min-w-full rounded-xl">
            <thead>
                <tr class="bg-gray-200">
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">No</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Kelas</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Nama Siswa</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">NIS</th>
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">R</th>
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">B</th>
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">SB</th>
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @forelse ($siswa as $student)
                    <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">{{ $loop->iteration + ($siswa->currentPage() - 1) * $siswa->perPage() }}</td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">{{ $student->kelas->nama_kelas }}</td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">{{ $student->nama_siswa }}</td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">{{ $student->nis }}</td>
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">{{ $student->ringan_count }}</td>
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">{{ $student->berat_count }}</td>
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">{{ $student->sangat_berat_count }}</td>
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">
                        <div class="flex items-center justify-center space-x-2">
                                <!-- Tombol Detail -->
                                <a href="{{ route('sanksi.show', $student) }}" class="p-2  rounded-full  group transition-all duration-500  flex item-center">
                                        <svg  xmlns="http://www.w3.org/2000/svg" width="20" height="20"  
                                            fill="green" viewBox="0 0 24 24" >
                                            <path class="fill-green-600" d="M7 10h10v2H7zM7 14h7v2H7z"></path><path d="M19 3h-2c0-.55-.45-1-1-1H8c-.55 0-1 .45-1 1H5c-1.1 0-2 .9-2 2v15c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2m0 17H5V5h2v2h10V5h2z"></path>
                                        </svg>
                                    </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-3 px-6 text-center text-gray-500">Belum ada siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Info jumlah data & pagination -->
        <div class="flex flex-col md:flex-row justify-between p-2 text-sm">
            <div>
                Show <span class="font-bold">{{ $siswa->firstItem() }}</span> to
                <span class="font-bold">{{ $siswa->lastItem() }}</span> from  
                <span class="font-bold">{{ $siswa->total() }}</span>
            </div>

            <div class="">
                {{ $siswa->withQueryString()->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection