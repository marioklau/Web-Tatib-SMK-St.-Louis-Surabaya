@extends('layouts.main')

@section('title', 'Laporan Siswa')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Laporan Siswa Berdasarkan Kelas</h1>

    <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
        <select name="kelas_id" class="border rounded px-4 py-2">
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        @if ($selectedKelas)
            <a href="{{ route('laporan.exportPdf', ['kelas_id' => $selectedKelas]) }}" class="ml-4 bg-red-500 text-white px-4 py-2 rounded">
                Export PDF
            </a>
        @endif
    </form>

    @if ($siswa->count())
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border">No</th>
                    <th class="py-2 px-4 border">Kelas</th>
                    <th class="py-2 px-4 border">Nama Siswa</th>
                    <th class="py-2 px-4 border">Jenis Kelamin</th>
                    <th class="py-2 px-4 border">Jumlah Pelanggaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $index => $item)
                    <tr>
                        <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                        <td class="py-2 px-4 border">{{ $item->kelas->nama_kelas }}</td>
                        <td class="py-2 px-4 border">{{ $item->nama_siswa }}</td>
                        <td class="py-2 px-4 border">{{ $item->jenis_kelamin }}</td>
                        <td class="py-2 px-4 border">{{ $item->pelanggaran_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-600 mt-4">Tidak ada siswa untuk kelas ini.</p>
    @endif
@endsection
