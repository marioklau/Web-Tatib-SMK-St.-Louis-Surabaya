@extends('layouts.main')

@section('title', 'Edit Pelanggaran')

@section('content')

<!-- CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery dan JS Select2 (Pastikan jQuery dimuat sebelum Select2) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-6">Edit Pelanggaran</h1>

    <form action="{{ route('input-pelanggaran.update', $pelanggaran->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Siswa (Read-only, but its ID is submitted via hidden input) -->
        <div>
            <label class="block mb-1 text-sm font-medium">Nama Siswa</label>
            <p class="font-medium text-gray-900">{{ $pelanggaran->siswa->nama_siswa }}</p>
            <input type="hidden" name="siswa_id" value="{{ $pelanggaran->siswa_id }}">
        </div>

        <!-- Kategori Pelanggaran (Read-only, but its ID is submitted via hidden input) -->
        <div>
            <label class="block mb-1 text-sm font-medium">Kategori Pelanggaran</label>
            <p class="font-medium text-gray-900">{{ $pelanggaran->kategori->nama_kategori }}</p>
            <input type="hidden" name="kategori_id" value="{{ $pelanggaran->kategori_id }}">
        </div>

        <!-- Pilih Jenis -->
        <div>
            <label for="jenis_id" class="block text-sm font-medium">Jenis Pelanggaran</label>
            <select name="jenis_id" id="jenis-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- Pilih Jenis Pelanggaran --</option>
                @foreach($jenis as $j)
                    <option
                        value="{{ $j->id }}"
                        data-kategori="{{ $j->kategori_id }}"
                        {{ $pelanggaran->jenis_id == $j->id ? 'selected' : '' }}>
                        {{ $j->bentuk_pelanggaran }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Pilih Sanksi -->
        <div>
            <label for="sanksi_id" class="block text-sm font-medium">Sanksi</label>
            <select name="sanksi_id" id="sanksi-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- Pilih Sanksi --</option>
                @foreach($sanksi as $s)
                    <option
                        value="{{ $s->id }}"
                        data-kategori="{{ $s->kategori_id }}"
                        {{ $pelanggaran->sanksi_id == $s->id ? 'selected' : '' }}>
                        {{ $s->keputusan_tindakan }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-medium">Status</label>
            <div class="mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="Belum" {{ $pelanggaran->status == 'Belum' ? 'checked' : '' }} class="form-radio text-red-600">
                    <span class="ml-2 text-red-600 font-semibold">Belum</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="status" value="Sudah" {{ $pelanggaran->status == 'Sudah' ? 'checked' : '' }} class="form-radio text-green-600">
                    <span class="ml-2 text-green-600 font-semibold">Sudah</span>
                </label>
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('input-pelanggaran.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenis-select');
        const sanksiSelect = document.getElementById('sanksi-select');
        const currentKategoriId = {{ $pelanggaran->kategori_id }}; // Get the current category ID of the violation

        function filterOptionsByKategori(selectElement, kategoriId) {
            Array.from(selectElement.options).forEach(opt => {
                // Ensure the 'kategoriId' matches the current violation's category ID,
                // or if it's the placeholder option with no data-kategori attribute.
                if (opt.dataset.kategori && opt.dataset.kategori != kategoriId) {
                    opt.style.display = 'none';
                } else {
                    opt.style.display = '';
                }
            });

            // If the currently selected option is now hidden, re-select a visible one or the default
            let selectedOption = selectElement.querySelector('option:checked');
            if (selectedOption && selectedOption.style.display === 'none') {
                 // Try to select the placeholder or the first visible option
                let firstVisibleOption = selectElement.querySelector('option[style*="display:"]');
                if (firstVisibleOption) {
                    selectElement.value = firstVisibleOption.value;
                } else {
                    selectElement.value = ''; // Fallback to no selection
                }
            }
        }

        // Apply filtering on page load
        filterOptionsByKategori(jenisSelect, currentKategoriId);
        filterOptionsByKategori(sanksiSelect, currentKategoriId);

        // Initialize Select2 on both dropdowns
        $(jenisSelect).select2();
        $(sanksiSelect).select2();
    });
</script>
@endsection
