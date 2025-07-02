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
                            data-sb="{{ $student->sangat_berat_count ?? 0 }}">
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
                            data-kategori-nama="{{ $j->kategori->nama_kategori }}">
                            {{ $j->bentuk_pelanggaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" id="kategori-display" class="border-2 text-gray-600 " readonly>
                <input type="hidden" name="kategori_id" id="kategori-id">
            </div>
        </div>

        <div class="mt-4">
            <label class="block mb-1">Pilih Pembinaan</label>
            <select name="sanksi_id" id="sanksi-select" class="w-full border p-2" required>
                {{-- Opsi akan ditambahkan via JS --}}
            </select>
        </div>

        <div class="mt-4">
            <label class="block mt-4 mb-1">Pilih Keputusan Tindakan</label>
            <select name="keputusan_tindakan_id" id="keputusan-select" class="border p-2" required>
                <option value="">-- Pilih Keputusan --</option>
                @foreach ($keputusanTindakan as $keputusan)
                    <option value="{{ $keputusan->id }}">{{ $keputusan->nama_keputusan }}</option>
                @endforeach
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
        $('#siswa-select, #jenis-select, #sanksi-select').select2({
            placeholder: "-- Pilih --",
            allowClear: true,
            minimumResultsForSearch: 0
        });

        const allSanksiData = @json($sanksi);

        function updateCounts() {
            const selected = $('#siswa-select option:selected');
            $('#count-r').val(selected.data('r') ?? 0);
            $('#count-b').val(selected.data('b') ?? 0);
            $('#count-sb').val(selected.data('sb') ?? 0);
        }

        $('#siswa-select').on('change', updateCounts);
        updateCounts();

        $('#jenis-select').on('change', function () {
            const selectedJenis = $(this).find(':selected');
            const kategoriId = selectedJenis.data('kategori-id');
            const kategoriNama = selectedJenis.data('kategori-nama');

            $('#kategori-id').val(kategoriId);
            $('#kategori-display').val(kategoriNama);

            $('#sanksi-select').empty();
            const filteredSanksi = allSanksiData.filter(sanksi => sanksi.kategori_id == kategoriId);

            if (filteredSanksi.length > 0) {
                $('#sanksi-select').append(new Option('-- Pilih Sanksi --', '', false, false));
                filteredSanksi.forEach(sanksi => {
                    const newOption = new Option(sanksi.nama_sanksi, sanksi.id, false, false);
                    $(newOption).data('pembinaan', sanksi.pembinaan_text);
                    $(newOption).data('keputusan', sanksi.keputusan_tindakan);
                    $('#sanksi-select').append(newOption);
                });
            } else {
                $('#sanksi-select').append(new Option('-- Tidak ada sanksi untuk kategori ini --', '', false, false));
            }

            $('#sanksi-select').val('').trigger('change');
            updateKeputusanDisplay(); // Memanggil fungsi untuk memperbarui dropdown keputusan
        });

        function updateKeputusanDisplay() {
            const selectedSanksiOption = $('#sanksi-select option:selected');
            const keputusanSelect = $('#keputusan-select');

            keputusanSelect.empty().append(new Option('-- Pilih Keputusan --', '', false, false));

            if (selectedSanksiOption.val() && selectedSanksiOption.val() !== '') {
                const keputusanArray = selectedSanksiOption.data('keputusan'); // Ambil data keputusan dari sanksi yang dipilih

                if (Array.isArray(keputusanArray)) {
                    keputusanArray.forEach(k => {
                        keputusanSelect.append(new Option(k, k, false, false));
                    });
                }
            }
        }
        

        $('#sanksi-select').on('change', updateKeputusanDisplay);
        $('#jenis-select').trigger('change');
    });
</script>

@endsection
