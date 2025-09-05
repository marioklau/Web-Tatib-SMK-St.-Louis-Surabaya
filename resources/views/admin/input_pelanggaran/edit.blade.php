@extends('layouts.main')

@section('title', 'Edit Pelanggaran Siswa')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-6">Edit Pelanggaran Siswa</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('input_pelanggaran.update', $input_pelanggaran->id) }}" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <!-- Nama Siswa (Tidak Bisa Diubah) -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Nama Siswa</label>
            <div class="bg-gray-100 p-3 rounded border border-gray-300">
                <p class="text-gray-800">
                    {{ $input_pelanggaran->siswa->nama_siswa }} 
                    <span class="text-gray-600">({{ $input_pelanggaran->siswa->kelas->kode_kelas ?? 'Tanpa Kelas' }})</span>
                </p>
                <input type="hidden" name="siswa_id" value="{{ $input_pelanggaran->siswa_id }}">
            </div>
        </div>

        <!-- Jenis dan Kategori Pelanggaran (Tidak Bisa Diubah) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Jenis Pelanggaran</label>
                <div class="bg-gray-100 p-3 rounded border border-gray-300">
                    <p class="text-gray-800">
                        {{ $input_pelanggaran->jenis->bentuk_pelanggaran ?? 'Tidak ada data' }}
                    </p>
                    <input type="hidden" name="jenis_id" value="{{ $input_pelanggaran->jenis_id }}">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                <div class="bg-gray-100 p-3 rounded border border-gray-300">
                    <p class="text-gray-800">
                        {{ $input_pelanggaran->kategori->nama_kategori ?? 'Tidak ada data' }}
                    </p>
                    <input type="hidden" name="kategori_id" value="{{ $input_pelanggaran->kategori_id }}">
                </div>
            </div>
        </div>

        <!-- Bobot dan Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        
            <div>
                <label class="block text-gray-700 font-medium mb-2">Status</label>
                <select name="status" class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Belum" {{ old('status', $input_pelanggaran->status) == 'Belum' ? 'selected' : '' }}>Belum Diproses</option>
                    <option value="Sudah" {{ old('status', $input_pelanggaran->status) == 'Sudah' ? 'selected' : '' }}>Sudah Diproses</option>
                </select>
            </div>
        </div>

        <!-- Alur Pembinaan -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <label class="block text-gray-700 font-medium mb-3">Alur Pembinaan:</label>
            <div id="alur-pembinaan" class="text-sm text-gray-700">
                @if($input_pelanggaran->sanksi)
                    <div class="space-y-2">
                        <h4 class="font-semibold">Tingkat (Bobot {{ $input_pelanggaran->sanksi->bobot_min ?? 0 }}-{{ $input_pelanggaran->sanksi->bobot_max ?? 0 }}):</h4>
                        @if(is_array($input_pelanggaran->sanksi->nama_sanksi))
                            <ol class="list-decimal pl-5 space-y-1">
                                @foreach($input_pelanggaran->sanksi->nama_sanksi as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p class="text-gray-500">Tidak ada alur pembinaan tersedia.</p>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500">Tidak ada data sanksi terkait</p>
                @endif
            </div>
        </div>

        <!-- Keputusan Tindakan -->
        <div class="mb-8">
            <label class="block text-gray-700 font-medium mb-2">Pilih Keputusan Tindakan (Minimal 2)</label>
            <div id="keputusan-container" class="border p-3 rounded bg-gray-50 max-h-60 overflow-y-auto">
                <div class="space-y-2">
                    @php
                        $keputusanTerpilih = json_decode($input_pelanggaran->keputusan_tindakan_terpilih, true) ?? [];
                    @endphp

                    @if($input_pelanggaran->sanksi && is_array($input_pelanggaran->sanksi->keputusan_tindakan))
                        @foreach($input_pelanggaran->sanksi->keputusan_tindakan as $index => $k)
                            <div class="flex items-center">
                                <input type="checkbox" id="keputusan-{{ $index }}" 
                                    name="keputusan_checkbox[]" 
                                    value="{{ $k }}" 
                                    class="keputusan-checkbox mr-2"
                                    {{ in_array($k, old('keputusan_checkbox', $keputusanTerpilih)) ? 'checked' : '' }}>
                                <label for="keputusan-{{ $index }}" class="text-sm">{{ $k }}</label>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">Tidak ada pilihan keputusan tersedia.</p>
                    @endif
                </div>
                <div id="keputusan-error" class="text-red-500 text-sm mt-2 hidden">Pilih minimal 2 keputusan tindakan</div>
            </div>
            <input type="hidden" name="keputusan_tindakan_terpilih" id="keputusan-input" value="{{ old('keputusan_tindakan_terpilih', $input_pelanggaran->keputusan_tindakan_terpilih) }}">
        </div>


        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                Simpan Perubahan
            </button>
            <a href="{{ route('input_pelanggaran.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition duration-200 text-center">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
$(function () {
    const $keputusanInput = $('#keputusan-input');
    const $keputusanError = $('#keputusan-error');

    function updateKeputusanInput() {
        const selectedValues = $('.keputusan-checkbox:checked').map(function () {
            return this.value;
        }).get();

        if (selectedValues.length < 2) {
            $keputusanError.removeClass('hidden');
            $keputusanInput.val('');
        } else {
            $keputusanError.addClass('hidden');
            $keputusanInput.val(JSON.stringify(selectedValues));
        }
    }

    // Bind event
    $('.keputusan-checkbox').on('change', updateKeputusanInput);

    // Jalankan sekali saat halaman dimuat
    updateKeputusanInput();

    // Validasi sebelum submit
    $('form').on('submit', function (e) {
        const checked = $('.keputusan-checkbox:checked').length;
        if (checked < 2) {
            e.preventDefault();
            $keputusanError.removeClass('hidden');
            $('html, body').animate({ scrollTop: $('#keputusan-container').offset().top - 100 }, 500);
        }
    });
});
</script>


@endsection