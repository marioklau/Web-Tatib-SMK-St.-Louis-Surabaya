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
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Keputusan</th>
                    <th scope="col" class="p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($pelanggaran as $offense)
                <tr class="transition-all duration-500 hover:bg-gray-50" data-id="{{ $offense->id }}">
                    <!-- Isi tabel sama seperti di admin -->
                </tr>
                @empty
                <tr><td colspan="7" class="p-3 text-center">Belum ada data pelanggaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection