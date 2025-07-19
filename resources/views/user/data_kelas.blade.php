@extends('layouts.main')

@section('title', 'Data Kelas')

@section('content')
<div class="container mx-auto" x-data="{ openDeleteModal: false, deleteUrl: '', errorMessage: '' }">
    <h1 class="text-2xl font-semibold text-start mb-8">Data Kelas</h1>

    <!-- Tabel Kategori -->
    <div class="overflow-hidden">
        <table class="min-w-full rounded-xl">
            <thead>
                <tr class="bg-gray-200">
                    <th scope="col" class="p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">No</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Kode Kelas</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Nama Kelas</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Wali Kelas</th>
                    <th scope="col" class="p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Total Siswa</th>
                    <th scope="col" class="p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @forelse ($kelas as $class)
                    <tr class="bg-white transition-all duration-500 hover:bg-gray-50">
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">{{ $loop->iteration }}</td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">{{ $class->kode_kelas }}</td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">{{ $class->nama_kelas }}</td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">{{ $class->wali_kelas }}</td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-center text-gray-900">
                            {{ $class->siswa_count }}
                        </td>
                        <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-gray-900">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Tombol Detail -->
                                <a href="{{ route('sanksi.show', $class) }}" class="p-2  rounded-full  group transition-all duration-500  flex item-center">
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
                        <td colspan="7" class="py-3 px-6 text-center text-gray-500">Belum ada Kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection