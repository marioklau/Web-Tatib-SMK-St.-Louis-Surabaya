@extends('layouts.main')

@section('title', 'Form Input Pelanggaran')

@section('content')

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Form Input Pelanggaran</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('input-pelanggaran.store') }}" class="bg-white rounded shadow p-6">
        @csrf

        <!-- SISWA + R B SB -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 items-end">
            <!-- Siswa -->
            <div>
                <label class="block mb-1">Nama Siswa</label>
                <select name="siswa_id" id="siswa-select" class="w-full p-2 border rounded" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($siswa as $student)
                        <option 
                            value="{{ $student->id }}"
                            data-r="{{ $student->ringan_count ?? 0 }}"
                            data-b="{{ $student->berat_count ?? 0 }}"
                            data-sb="{{ $student->sangat_berat_count ?? 0 }}"
                        >
                            {{ $student->nama_siswa }} - ({{ $student->kelas->kode_kelas ?? 'Tanpa Kelas' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- R B SB Count -->
            <div class="flex items-end gap-2 col-span-3">
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">R</label>
                    <input type="text" id="count-r" class="w-14 p-1 text-xs border-2 rounded text-center bg-green-100 text-green-800" readonly>
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">B</label>
                    <input type="text" id="count-b" class="w-14 p-1 text-xs border-2 rounded text-center bg-yellow-100 text-yellow-800" readonly>
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">SB</label>
                    <input type="text" id="count-sb" class="w-14 p-1 text-xs border-2 rounded text-center bg-red-100 text-red-800" readonly>
                </div>
            </div>
        </div>

        <!-- JENIS & KATEGORI -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Jenis Pelanggaran -->
            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                <select name="jenis_id" id="jenis-select" class="w-full border rounded" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($jenis as $j)
                        <option 
                            value="{{ $j->id }}"
                            data-kategori-id="{{ $j->kategori->id }}"
                            data-kategori-nama="{{ $j->kategori->nama_kategori }}">
                            {{ $j->bentuk_pelanggaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" id="kategori-display" class="border-2 text-gray-600 bg-gray-100 rounded w-full" readonly>
                <input type="hidden" name="kategori_id" id="kategori-id">
            </div>
        </div>

        <!-- Sanksi -->
        <div class="mt-4">
            <label class="block mb-1">Sanksi</label>
            <select name="sanksi_id" id="sanksi-select" class="w-full border p-2 rounded" required>
                <option value="">-- Pilih Sanksi --</option>
                @foreach($sanksi as $s)
                    <option data-kategori="{{ $s->kategori_id }}" value="{{ $s->id }}">
                        {{ $s->nama_sanksi }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tombol -->
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <button type="button" onclick="history.back()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Kembali</button>
        </div>
    </form>
</div>

<!-- SCRIPT -->
<script>
    $(document).ready(function () {
        // Inisialisasi Select2
        $('#siswa-select, #jenis-select, #sanksi-select').select2({
            placeholder: "-- Pilih --",
            allowClear: true,
            minimumResultsForSearch: 0 // AKTIFKAN pencarian walau data sedikit
        });

        // ==== Update Count R/B/SB ====
        function updateCounts() {
            const selected = $('#siswa-select option:selected');
            $('#count-r').val(selected.data('r') ?? 0);
            $('#count-b').val(selected.data('b') ?? 0);
            $('#count-sb').val(selected.data('sb') ?? 0);
        }

        $('#siswa-select').on('change', updateCounts);
        updateCounts(); // initial load if any preselected

        // ==== Jenis => Update Kategori + Filter Sanksi ====
        $('#jenis-select').on('change', function () {
            const selected = $(this).find(':selected');
            const kategoriId = selected.data('kategori-id');
            const kategoriNama = selected.data('kategori-nama');

            $('#kategori-id').val(kategoriId);
            $('#kategori-display').val(kategoriNama);

            // Filter sanksi sesuai kategori
            $('#sanksi-select option').each(function () {
                const sanksiKategori = $(this).data('kategori');
                $(this).toggle(!sanksiKategori || sanksiKategori == kategoriId);
            });

            $('#sanksi-select').val(null).trigger('change');
        });
    });
</script>

@endsection
