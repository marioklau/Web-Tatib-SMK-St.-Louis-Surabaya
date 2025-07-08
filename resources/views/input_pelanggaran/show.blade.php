@extends('layouts.main')

@section('title', 'Detail Pelanggaran Siswa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Detail Pelanggaran Siswa</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header Card -->
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-white text-xl font-semibold">Informasi Pelanggaran</h2>
        </div>

        <!-- Body Card -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Data Siswa -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-200">Data Siswa</h3>
                    <table class="w-full">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="font-semibold py-3 w-1/3">Nama Siswa</td>
                                <td class="py-3">{{ $pelanggaran->siswa->nama_siswa }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-3">NIS</td>
                                <td class="py-3">{{ $pelanggaran->siswa->nis ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-3">Kelas</td>
                                <td class="py-3">{{ $pelanggaran->siswa->kelas->nama_kelas ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Detail Pelanggaran -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-200">Detail Pelanggaran</h3>
                    <table class="w-full">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="font-semibold py-3 w-1/3">Jenis Pelanggaran</td>
                                <td class="py-3">{{ $pelanggaran->jenis->bentuk_pelanggaran }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-3">Kategori</td>
                                <td class="py-3">{{ $pelanggaran->kategori->nama_kategori }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-3">Bobot Poin</td>
                                <td class="py-3">{{ $pelanggaran->poin_pelanggaran }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-3">Status</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $pelanggaran->status == 'Sudah' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $pelanggaran->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-3">Waktu Input</td>
                                <td class="py-3">{{ $pelanggaran->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Alur Pembinaan -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-200">Alur Pembinaan</h3>
                @if($pelanggaran->sanksi && is_array($pelanggaran->sanksi->nama_sanksi))
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="mb-3">
                            <h4 class="font-medium text-gray-700">Keputusan Tindakan:</h4>
                            <p class="text-gray-800 mt-1">{{ $pelanggaran->keputusan_tindakan_terpilih }}</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700">Tahapan Pembinaan:</h4>
                            <ol class="list-decimal pl-5 space-y-1 mt-2">
                                @foreach($pelanggaran->sanksi->nama_sanksi as $item)
                                    <li class="text-gray-800">{{ $item }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-gray-500">Tidak ada data alur pembinaan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer Card -->
        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200">
            <a href="{{ route('input_pelanggaran.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection