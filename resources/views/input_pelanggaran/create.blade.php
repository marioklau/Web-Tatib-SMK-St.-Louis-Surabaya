@extends('layouts.main')

@section('title', 'Form Input Pelanggaran')

@section('content')

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

    <form method="POST" action="{{ route('input_pelanggaran.store') }}" class="bg-white rounded shadow p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 items-end">
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
                            {{ old('siswa_id') == $student->id ? 'selected' : '' }}
                        >
                            {{ $student->nama_siswa }} - ({{ $student->kelas->kode_kelas ?? 'Tanpa Kelas' }})
                        </option>
                    @endforeach
                </select>
            </div>

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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                <select name="jenis_id" id="jenis-select" class="w-full border" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($jenis as $j)
                        <option
                            value="{{ $j->id }}"
                            data-kategori-id="{{ $j->kategori->id }}"
                            data-kategori-nama="{{ $j->kategori->nama_kategori }}"
                            {{ old('jenis_id') == $j->id ? 'selected' : '' }}
                        >
                            {{ $j->bentuk_pelanggaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" id="kategori-display" class="border-2 text-gray-600 w-full p-2" readonly>
                <input type="hidden" name="kategori_id" id="kategori-id">
            </div>
        </div>

        <div class="mt-4">
            <label class="block mb-1">Pilih Sanksi/Pembinaan</label>
            <select name="sanksi_id" id="sanksi-select" class="w-full border p-2" required>
                {{-- Opsi akan ditambahkan via JS --}}
            </select>
        </div>

        <div class="mt-4">
            <label class="block mt-4 mb-1">Pilih Keputusan Tindakan</label>
            {{-- Nama input diubah menjadi keputusan_tindakan_terpilih --}}
            <select name="keputusan_tindakan_terpilih" id="keputusan-select" class="w-full border p-2" required>
                <option value="">-- Pilih Keputusan --</option>
                {{-- Opsi akan ditambahkan via JS berdasarkan sanksi yang dipilih --}}
            </select>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-2 py-1 hover:bg-blue-700">Simpan</button>
            <button type="button" onclick="history.back()" class="bg-gray-200 text-gray-700 px-2 py-1 hover:bg-gray-300">Kembali</button>
        </div>
    </form>
</div>


<script>
    $(document).ready(function () {
        $('#siswa-select, #jenis-select, #sanksi-select, #keputusan-select').select2({
            placeholder: "-- Pilih --",
            allowClear: true,
            // minimumResultsForSearch: 0 // Hapus baris ini jika Anda ingin fitur pencarian selalu aktif
        });

        const allSanksiData = @json($sanksi); // Pastikan ini adalah array of objects dari model Sanksi

        function updateCounts() {
            const selected = $('#siswa-select option:selected');
            $('#count-r').val(selected.data('r') ?? 0);
            $('#count-b').val(selected.data('b') ?? 0);
            $('#count-sb').val(selected.data('sb') ?? 0);
        }

        $('#siswa-select').on('change', updateCounts);
        updateCounts(); // Panggil saat halaman pertama kali dimuat untuk nilai default

        $('#jenis-select').on('change', function () {
            const selectedJenis = $(this).find(':selected');
            const kategoriId = selectedJenis.data('kategori-id');
            const kategoriNama = selectedJenis.data('kategori-nama');

            $('#kategori-id').val(kategoriId);
            $('#kategori-display').val(kategoriNama);

            $('#sanksi-select').empty().append(new Option('-- Pilih Sanksi --', '', false, false));
            $('#keputusan-select').empty().append(new Option('-- Pilih Keputusan --', '', false, false)); // Reset juga keputusan

            const filteredSanksi = allSanksiData.filter(sanksi => sanksi.kategori_id == kategoriId);

            if (filteredSanksi.length > 0) {
                filteredSanksi.forEach(sanksi => {
                    // Pastikan nama_sanksi adalah array dan ambil elemen pertama jika ada
                    const namaSanksiDisplay = Array.isArray(sanksi.nama_sanksi) ? sanksi.nama_sanksi[0] : sanksi.nama_sanksi;
                    const newOption = new Option(namaSanksiDisplay, sanksi.id, false, false);
                    $(newOption).data('keputusan', sanksi.keputusan_tindakan); // Simpan array keputusan
                    $('#sanksi-select').append(newOption);
                });
            } else {
                $('#sanksi-select').append(new Option('-- Tidak ada sanksi untuk kategori ini --', '', false, false));
            }

            $('#sanksi-select').val('').trigger('change'); // Reset dan trigger change untuk memperbarui keputusan
        });

        function updateKeputusanDisplay() {
        const selectedSanksiOption = $('#sanksi-select option:selected');
        const keputusanSelect = $('#keputusan-select');

        keputusanSelect.empty().append(new Option('-- Pilih Keputusan --', '', false, false));

        if (selectedSanksiOption.val() && selectedSanksiOption.val() !== '') {
            const keputusanArray = selectedSanksiOption.data('keputusan');

            console.log('Selected Sanksi ID:', selectedSanksiOption.val());
            console.log('Keputusan array from selected Sanksi:', keputusanArray);

            if (Array.isArray(keputusanArray)) {
                keputusanArray.forEach(k => {
                    keputusanSelect.append(new Option(k, k, false, false));
                });
                console.log('Keputusan dropdown updated with:', keputusanArray);
            } else {
                console.log('Keputusan data is not an array or is missing.');
            }
        } else {
            console.log('No Sanksi selected.');
        }
    }

        $('#sanksi-select').on('change', updateKeputusanDisplay);

        // Panggil trigger change pada #jenis-select saat halaman pertama kali dimuat
        // ini akan mengisi dropdown sanksi dan keputusan sesuai dengan jenis yang mungkin sudah terpilih (misal dari old())
        $('#jenis-select').trigger('change');

        // Jika ada old('sanksi_id') setelah validasi gagal
        @if(old('sanksi_id'))
            $('#sanksi-select').val("{{ old('sanksi_id') }}").trigger('change');
            // Untuk keputusan, kita perlu logic tambahan jika ingin mempertahankan old('keputusan_tindakan_terpilih')
            // Ini akan mengisi dropdown keputusan berdasarkan sanksi yang dipilih,
            // kemudian mencoba memilih old('keputusan_tindakan_terpilih')
            setTimeout(() => {
                @if(old('keputusan_tindakan_terpilih'))
                    $('#keputusan-select').val("{{ old('keputusan_tindakan_terpilih') }}").trigger('change');
                @endif
            }, 100); // Sedikit delay untuk memastikan sanksi-select sudah terisi opsinya
        @endif
    });
</script>

@endsection