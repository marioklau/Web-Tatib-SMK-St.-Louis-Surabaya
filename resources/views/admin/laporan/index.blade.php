@extends('layouts.main')

@section('title', 'Laporan Siswa')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Laporan Pelanggaran Siswa</h1>

    <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Filter Kelas -->
            <div class="rounded-md">
                <label for="kelas_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="border rounded px-2 py-1">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $selectedKelas == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Periode -->
            <div class="rounded-md">
                <label for="periode" class="text-sm font-medium text-gray-700 whitespace-nowrap">Periode</label>
                <select name="periode" id="periode" class="border rounded px-2 py-1">
                    <option value="all" {{ $selectedPeriode == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="hari" {{ $selectedPeriode == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu" {{ $selectedPeriode == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan" {{ $selectedPeriode == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ $selectedPeriode == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ $selectedPeriode == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            <!-- Filter Custom Date (akan muncul hanya ketika custom dipilih) -->
            <div id="customDateFilter" style="{{ $selectedPeriode != 'custom' ? 'display:none;' : '' }}">
                <label for="start_date" class="mr-2">Dari:</label>
                <input type="date" name="start_date" id="start_date" 
                    value="{{ $startDate ?? '' }}" class="border rounded px-2 py-1">
                
                <label for="end_date" class="ml-2 mr-2">Sampai:</label>
                <input type="date" name="end_date" id="end_date" 
                    value="{{ $endDate ?? '' }}" class="border rounded px-2 py-1">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow text-sm">
                Filter
            </button>

            <div>
                <a href="{{ route('laporan.exportPdf', ['kelas_id' => $selectedKelas, 'periode' => $selectedPeriode, 'start_date' => $startDate ?? '', 'end_date' => $endDate ?? '']) }}"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow text-sm">
                    Export PDF
                </a>
            </div>
        </div>
    </form>

    @if ($siswa->count())
        <div class="overflow-x-auto">
            <table class="min-w-full rounded-xl">
                <thead>
                    <tr class="bg-gray-200">
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
                    @php $nomor = 1; @endphp
                    @foreach ($siswa as $item)
                        @foreach ($item->pelanggaran as $pelanggaran)
                            <tr class="border-b">
                                @if ($loop->first)
                                    <td class="p-2 text-center align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $nomor++ }}</td>
                                    <td class="p-2 align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $item->kelas->nama_kelas }}</td>
                                    <td class="p-2 align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nama_siswa }}</td>
                                    <td class="p-2 align-top" rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nis }}</td>
                                @endif

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

    @push('scripts')
    <script>
        document.getElementById('periode').addEventListener('change', function() {
            const customDateFilter = document.getElementById('customDateFilter');
            if (this.value === 'custom') {
                customDateFilter.style.display = 'block';
            } else {
                customDateFilter.style.display = 'none';
            }
        });
    </script>
    @endpush
@endsection