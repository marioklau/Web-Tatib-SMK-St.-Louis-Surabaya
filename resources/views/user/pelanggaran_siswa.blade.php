@extends('layouts.main')

@section('title', 'Pelanggaran Siswa')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Pelanggaran Siswa</h1>

    <!-- Filter Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <h2 class="text-lg font-normal whitespace-nowrap">Riwayat Pelanggaran</h2>

        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 ml-auto">
            <!-- Filter Form -->
            <form action="{{ route('user.pelanggaran_siswa') }}" method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <!-- Status Filter -->
                <div class="flex items-center gap-2">
                    <label for="status" class="text-sm font-medium text-gray-700 whitespace-nowrap">Status</label>
                    <select id="status" name="status" class="border border-gray-300 rounded-md py-1 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Sudah" {{ request('status') == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                        <option value="Belum" {{ request('status') == 'Belum' ? 'selected' : '' }}>Belum</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="flex items-center gap-2">
                    <label for="date" class="text-sm font-medium text-gray-700 whitespace-nowrap">Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}" 
                        class="border border-gray-300 rounded-md py-1 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded-md hover:bg-blue-700 transition duration-300 whitespace-nowrap">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-hidden">
        <table class="min-w-full rounded-xl">
            <!-- Tabel sama seperti di admin tapi tanpa kolom Aksi -->
            <thead>
                <tr class="bg-gray-200">
                    <th scope="col" class="p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">No</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Nama</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Kelas</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Jenis</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Alur Pembinaan</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Keputusan</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Status</th>
                    <th scope="col" class="p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($pelanggaran as $offense)
                <tr class="transition-all duration-500 hover:bg-gray-50" data-id="{{ $offense->id }}">
                    <td class="p-1 whitespace-nowrap text-center text-xs mleading-6 font-medium text-gray-900">{{ $loop->iteration }}</td>
                    <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-left text-gray-900">{{ $offense->siswa->nama_siswa }}</td>
                    <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-left text-gray-900">{{ $offense->siswa->kelas->nama_kelas ?? '-' }}</td>
                    @php
                        $words = explode(' ', $offense->jenis->bentuk_pelanggaran);
                        $firstLine = implode(' ', array_slice($words, 0, 5));
                        $secondLine = implode(' ', array_slice($words, 5));
                    @endphp
                    <td class="p-1 whitespace-normal text-xs leading-6 font-medium text-gray-900 max-w-xs">
                        {{ $firstLine }}@if($secondLine)<br>{{ $secondLine }}@endif
                    </td>

                    {{-- Menampilkan nama_sanksi dari array --}}
                    <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-left text-gray-900">
                        @if ($offense->sanksi && is_array($offense->sanksi->nama_sanksi))
                            <ul class="list-disc pl-5">
                                @foreach ($offense->sanksi->nama_sanksi as $sanksi)
                                    <li>{{ $sanksi }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ $offense->sanksi->nama_sanksi ?? '-' }}
                        @endif
                    </td>

                    {{-- Menampilkan keputusan_tindakan_terpilih yang disimpan sebagai string di Pelanggaran --}}
                    <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-left text-gray-900">
                        {{ $offense->keputusan_tindakan_terpilih ?? '-' }}
                    </td>

                    <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-left text-gray-900">
                        <span class="font-bold {{ $offense->status === 'Sudah' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $offense->status ?? 'Belum' }}
                        </span>
                    </td>
                    <td class="p-1 whitespace-nowrap text-xs leading-6 font-medium text-center text-gray-900">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('input_pelanggaran.show', $offense->id) }}" class="p-2 rounded-full group transition-all duration-500 flex items-center hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="green" viewBox="0 0 24 24" >
                                    <path class="fill-green-600" d="M7 10h10v2H7zM7 14h7v2H7z"></path><path d="M19 3h-2c0-.55-.45-1-1-1H8c-.55 0-1 .45-1 1H5c-1.1 0-2 .9-2 2v15c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2m0 17H5V5h2v2h10V5h2z"></path>
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="p-3 text-center bg-gray-50">Belum ada data pelanggaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection