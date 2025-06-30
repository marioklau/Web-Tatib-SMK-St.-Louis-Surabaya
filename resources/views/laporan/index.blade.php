@extends('layouts.main')

@section('title', 'Laporan Siswa')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Laporan Pelanggaran Siswa</h1>

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
    <div class="overflow-hidden">
    <table class="min-w-full rounded-xl">
            <thead>
                <tr class="bg-gray-200">
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">No</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Kelas</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Nama Siswa</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">NIS</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Jenis Pelanggaran</th>
                    <th scope="col" class=" p-1 text-left text-sm leading-6 font-semibold text-gray-900 capitalize">Sanksi Pelanggaran</th>
                    <th scope="col" class=" p-1 text-center text-sm leading-6 font-semibold text-gray-900 capitalize">Status Pelanggaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $index => $item)
                    <tr>
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">{{ $index + 1 }}</td>
                        <td class="p-1 whitespace-nowrap text-left text-xs leading-6 font-medium text-gray-900">{{ $item->kelas->nama_kelas }}</td>
                        <td class="p-1 whitespace-nowrap text-left text-xs leading-6 font-medium text-gray-900">{{ $item->nama_siswa }}</td>
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">{{ $item->nis }}</td>
                        <td class="p-1 whitespace-nowrap text-left text-xs leading-6 font-medium text-gray-900">
                            @php
                                $jenisPelanggaran = $item->pelanggaran->pluck('jenis.bentuk_pelanggaran')->unique()->implode(', ');
                            @endphp
                            {{ $jenisPelanggaran ?: '-' }}
                        </td>
                        <td class="p-1 whitespace-nowrap text-left text-xs leading-6 font-medium text-gray-900">
                            @php
                                $sanksi = $item->pelanggaran->pluck('sanksi.keputusan_tindakan')->unique()->implode(', ');
                            @endphp
                            {{ $sanksi ?: '-' }}
                        </td>
                        <td class="p-1 whitespace-nowrap text-center text-xs leading-6 font-medium text-gray-900">
                            @php
                                $statuses = $item->pelanggaran->pluck('status')->unique();
                            @endphp

                            @if ($statuses->isEmpty())
                                <span class="text-gray-500">-</span>
                            @else
                                @foreach ($statuses as $status)
                                    <span class="font-bold {{ $status === 'Sudah' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $status }}
                                    </span>
                                    @if (!$loop->last), @endif
                                @endforeach
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-gray-600 mt-4">Tidak ada siswa untuk kelas ini.</p>
    @endif
@endsection
