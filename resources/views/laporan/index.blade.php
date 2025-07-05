@extends('layouts.main')

@section('title', 'Laporan Siswa')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Laporan Pelanggaran Siswa</h1>

    {{-- Bagian form filter tidak perlu diubah --}}
    <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
        <div class="flex items-center flex-wrap gap-4">
            <div>
                <label for="kelas_id" class="mr-2">Filter Kelas:</label>
                <select name="kelas_id" id="kelas_id" onchange="this.form.submit()" class="border rounded px-2 py-1">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('laporan.exportPdf', ['kelas_id' => $selectedKelas]) }}"
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow text-sm">
                Export PDF
            </a>
        </div>
    </form>

    @if ($siswa->count())
        <div class="overflow-x-auto">
            <table class="min-w-full rounded-xl">
                <thead>
                    <tr class="bg-gray-200">
                        {{-- Perubahan 1: Header tabel disesuaikan --}}
                        <th class="p-2 text-center text-sm font-semibold text-gray-900">No</th>
                        <th class="p-2 text-left text-sm font-semibold text-gray-900">Kelas</th>
                        <th class="p-2 text-left text-sm font-semibold text-gray-900">Nama Siswa</th>
                        <th class="p-2 text-left text-sm font-semibold text-gray-900">NIS</th>
                        <th class="p-2 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                        <th class="p-2 text-left text-sm font-semibold text-gray-900">Pelanggaran</th>
                        <th class="p-2 text-left text-sm font-semibold text-gray-900">Sanksi</th>
                        <th class="p-2 text-center text-sm font-semibold text-gray-900">Status</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Perubahan 2: Struktur loop diubah total untuk menampilkan 1 baris per pelanggaran --}}
                    @php $nomor = 1; @endphp
                    @foreach ($siswa as $item)
                        @foreach ($item->pelanggaran as $pelanggaran)
                            <tr class="border-b">
                                {{-- Menampilkan data siswa hanya di baris pertama pelanggaran dengan rowspan --}}
                                @if ($loop->first)
                                    <td class="p-2 text-center align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $nomor++ }}</td>
                                    <td class="p-2 align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $item->kelas->nama_kelas }}</td>
                                    <td class="p-2 align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nama_siswa }}</td>
                                    <td class="p-2 align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nis }}</td>
                                @endif

                                {{-- Kolom ini akan tampil di setiap baris, karena spesifik per pelanggaran --}}
                                <td class="p-2">{{ $pelanggaran->created_at->format('d M Y') }}</td>
                                <td class="p-2">{{ $pelanggaran->jenis->bentuk_pelanggaran ?? '-' }}</td>
                                <td class="p-2">{{ is_array($pelanggaran->sanksi->keputusan_tindakan) ? implode(', ', $pelanggaran->sanksi->keputusan_tindakan) : ($pelanggaran->sanksi->keputusan_tindakan ?? '-') }}</td>
                                <td class="p-2 text-center">
                                    <span class="font-bold {{ $pelanggaran->status === 'Sudah' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $pelanggaran->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 mt-4">Tidak ada data pelanggaran yang dapat ditampilkan.</p>
    @endif
@endsection