@extends('layouts.main')

@section('title', 'Form Input Pelanggaran')

@section('content')

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- JS -->
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

        <!-- PILIH SISWA + COUNTS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 items-end">
            <!-- Nama Siswa -->
            <div>
                <label class="block mb-1">Nama Siswa</label>
                <select name="siswa_id" class="w-full p-2 border rounded" id="siswa-select" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($siswa as $student)
                        <option 
                            value="{{ $student->id }}"
                            data-ringan="{{ $student->ringan_count ?? 0 }}"
                            data-berat="{{ $student->berat_count ?? 0 }}"
                            data-sangat-berat="{{ $student->sangat_berat_count ?? 0 }}"
                        >
                            {{ $student->nama_siswa }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ringan, Berat, Sangat Berat -->
            <div class="flex items-end gap-2 col-span-3">
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">R</label>
                    <input type="text" id="ringan-count" class="w-14 p-1 text-xs border-2 rounded text-center bg-green-100 text-green-800" readonly>
                </div>

                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">B</label>
                    <input type="text" id="berat-count" class="w-14 p-1 text-xs border-2 rounded text-center bg-yellow-100 text-yellow-800" readonly>
                </div>

                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">SB</label>
                    <input type="text" id="sangat-berat-count" class="w-14 p-1 text-xs border-2 rounded text-center bg-red-100 text-red-800" readonly>
                </div>
            </div>
        </div>

        <!-- JENIS & KATEGORI -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                <select name="jenis_id" class="w-full border rounded" id="jenis-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($jenis as $j)
                        <option value="{{ $j->id }}">{{ $j->bentuk_pelanggaran }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" id="kategori-display" class="border-2 text-gray-600 bg-gray-100 rounded w-full" readonly>
                <input type="hidden" name="kategori_id" id="kategori-id">
            </div>
        </div>

        <!-- Sanksi -->
        <div class="mt-4">
            <label class="block mb-1">Sanksi</label>
            <select name="sanksi_id" class="w-full border p-2 rounded" required id="sanksi-select">
                <option value="">-- Pilih Sanksi --</option>
                @foreach($sanksi as $s)
                    <option data-kategori="{{ $s->kategori_id }}" value="{{ $s->id }}">{{ $s->nama_sanksi }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tombol -->
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <button type="button" onclick="history.back()" class="inline-block px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Kembali</button>
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
            minimumResultsForSearch: Infinity
        });

        const jenisList = @json($jenis);

        // Sembunyikan sanksi terlebih dahulu
        $('#sanksi-select option').not(':first').hide();

        // Update count saat siswa dipilih
        $('#siswa-select').on('change', function () {
            const selected = $(this).find('option:selected');
            $('#ringan-count').val(selected.data('ringan') ?? 0);
            $('#berat-count').val(selected.data('berat') ?? 0);
            $('#sangat-berat-count').val(selected.data('sangat-berat') ?? 0);
        });

        // Update kategori dan filter sanksi saat jenis dipilih
        $('#jenis-select').on('change', function () {
            const jenisId = $(this).val();
            const selectedJenis = jenisList.find(j => j.id == jenisId);

            if (selectedJenis && selectedJenis.kategori) {
                const kategoriId = selectedJenis.kategori.id;
                $('#kategori-display').val(selectedJenis.kategori.nama_kategori);
                $('#kategori-id').val(kategoriId);

                // Tampilkan hanya sanksi sesuai kategori
                $('#sanksi-select option').each(function () {
                    const sanksiKategori = $(this).data('kategori');
                    if (!sanksiKategori || sanksiKategori == kategoriId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                $('#sanksi-select').val(null).trigger('change');
            } else {
                $('#kategori-display').val('-');
                $('#kategori-id').val('');
                $('#sanksi-select option').show();
                $('#sanksi-select').val(null).trigger('change');
            }
        });
    });
</script>

@endsection
