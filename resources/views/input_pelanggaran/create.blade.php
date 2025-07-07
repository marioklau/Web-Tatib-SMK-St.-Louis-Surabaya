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
                            data-total-bobot="{{ $student->total_bobot ?? 0 }}"
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
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">Total Bobot</label>
                    <input type="text" id="total-bobot-display" class="w-24 p-1 text-xs border-2 rounded text-center bg-blue-100 text-blue-800" readonly>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                <select name="jenis_id" id="jenis-select" class="w-full border" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($jenis as $j)
                        @if($j->bobot_poin >= 1)
                            <option
                                value="{{ $j->id }}"
                                data-kategori-id="{{ $j->kategori->id }}"
                                data-kategori-nama="{{ $j->kategori->nama_kategori }}"
                                data-bobot="{{ $j->bobot_poin }}"
                                {{ old('jenis_id') == $j->id ? 'selected' : '' }}
                            >
                                {{ $j->bentuk_pelanggaran }} <!-- Menghapus tampilan bobot ({{ $j->bobot_poin }}) -->
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" id="kategori-display" class="border-2 text-gray-600 text-center" readonly>
                <input type="hidden" name="kategori_id" id="kategori-id">
            </div>
        </div>

        <div class="mt-4">
            <label class="block mb-1">Pelanggaran Ke-</label>
            <input type="number" name="bobot" id="bobot-input" class="border-2 w-full p-2" 
                   min="1" max="200" required value="{{ old('bobot') }}">
        </div>

        <div class="mt-4 bg-gray-50 p-4 rounded">
            <label class="block mb-2 font-semibold">Alur Pembinaan:</label>
            <div id="alur-pembinaan" class="text-sm text-gray-700">
                <p class="text-gray-500">Pilih jenis pelanggaran untuk melihat alur pembinaan</p>
            </div>
        </div>

        <div class="mt-4">
            <label class="block mb-1">Pilih Keputusan Tindakan</label>
            <select name="keputusan_tindakan_terpilih" id="keputusan-select" class="w-full border p-2" required>
                <option value="">-- Pilih Keputusan --</option>
                {{-- Opsi akan diisi via JavaScript --}}
            </select>
        </div>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <button type="button" onclick="history.back()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Kembali</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        // Inisialisasi Select2
        $('#siswa-select, #jenis-select, #keputusan-select').select2({
            placeholder: "-- Pilih --",
            allowClear: true,
        });

        // Data sanksi dari controller
        const allSanksiData = @json($sanksi);

        // Fungsi untuk update counter pelanggaran
        function updateCounts() {
            const selected = $('#siswa-select option:selected');
            $('#count-r').val(selected.data('r') ?? 0);
            $('#count-b').val(selected.data('b') ?? 0);
            $('#count-sb').val(selected.data('sb') ?? 0);
            $('#total-bobot-display').val(selected.data('total-bobot') ?? 0);
        }

        // Event saat siswa berubah
        $('#siswa-select').on('change', updateCounts);

        // Event saat jenis pelanggaran berubah
        $('#jenis-select').on('change', function() {
            const selectedJenis = $(this).find(':selected');
            const kategoriId = selectedJenis.data('kategori-id');
            const kategoriNama = selectedJenis.data('kategori-nama');
            const bobotPelanggaran = selectedJenis.data('bobot');

            // Update tampilan kategori dan bobot
            $('#kategori-id').val(kategoriId);
            $('#kategori-display').val(kategoriNama);
            $('#bobot-input').val(bobotPelanggaran);

            // Tampilkan alur pembinaan berdasarkan kategori
            showAlurPembinaan(kategoriId, bobotPelanggaran);

            // Update opsi keputusan tindakan
            updateKeputusanOptions(kategoriId, bobotPelanggaran);
        });

        // Event saat bobot diubah manual
        $('#bobot-input').on('change', function() {
            const kategoriId = $('#kategori-id').val();
            const bobotPelanggaran = $(this).val();
            
            if (kategoriId && bobotPelanggaran) {
                showAlurPembinaan(kategoriId, bobotPelanggaran);
                updateKeputusanOptions(kategoriId, bobotPelanggaran);
            }
        });

        // Fungsi untuk menampilkan alur pembinaan
        function showAlurPembinaan(kategoriId, bobot) {
            const alurContainer = $('#alur-pembinaan');
            const sanksiKategori = allSanksiData
                .filter(s => s.kategori_id == kategoriId)
                // Urutkan dari bobot_min terkecil ke terbesar
                .sort((a, b) => (a.bobot_min || 0) - (b.bobot_min || 0));
            
            if (sanksiKategori.length > 0) {
                // Cari sanksi dengan range spesifik terlebih dahulu
                let sanksi = sanksiKategori.find(s => 
                    s.bobot_min && s.bobot_min > 0 && 
                    bobot >= s.bobot_min && bobot <= s.bobot_max
                );
                
                // Jika tidak ditemukan range spesifik, cari yang general (hanya bobot_max)
                if (!sanksi) {
                    sanksi = sanksiKategori.find(s => 
                        (!s.bobot_min || s.bobot_min === 0) && 
                        bobot <= s.bobot_max
                    );
                }

                // Default ke sanksi pertama jika tidak ditemukan
                sanksi = sanksi || sanksiKategori[0];

                let html = '<div class="space-y-2">';
                
                if (sanksi) {
                    // Tampilkan range bobot yang sesuai
                    if (!sanksi.bobot_min || sanksi.bobot_min === 0) {
                        html += `<h4 class="font-semibold">Tingkat (Bobot ${sanksi.bobot_max}):</h4>`;
                    } else {
                        html += `<h4 class="font-semibold">Tingkat (Bobot ${sanksi.bobot_min}-${sanksi.bobot_max}):</h4>`;
                    }
                    
                    if (Array.isArray(sanksi.nama_sanksi)) {
                        html += '<ol class="list-decimal pl-5">';
                        sanksi.nama_sanksi.forEach(item => {
                            html += `<li class="mb-1">${item}</li>`;
                        });
                        html += '</ol>';
                    } else {
                        html += '<p class="text-gray-500">Tidak ada alur pembinaan tersedia.</p>';
                    }
                }
                
                html += '</div>';
                alurContainer.html(html);
            } else {
                alurContainer.html('<p class="text-gray-500">Tidak ada alur pembinaan untuk kategori ini.</p>');
            }
        }

        // Fungsi untuk update opsi keputusan tindakan
        function updateKeputusanOptions(kategoriId, bobot) {
            const keputusanSelect = $('#keputusan-select');
            keputusanSelect.empty().append('<option value="">-- Pilih Keputusan --</option>');

            const sanksiKategori = allSanksiData
                .filter(s => s.kategori_id == kategoriId)
                .sort((a, b) => (a.bobot_min || 0) - (b.bobot_min || 0));
            
            if (sanksiKategori.length > 0) {
                // 1. Cari yang range spesifik dulu
                let sanksi = sanksiKategori.find(s => 
                    s.bobot_min && s.bobot_min > 0 && 
                    bobot >= s.bobot_min && bobot <= s.bobot_max
                );
                
                // 2. Jika tidak ketemu, cari yang general (hanya bobot_max)
                if (!sanksi) {
                    sanksi = sanksiKategori.find(s => 
                        (!s.bobot_min || s.bobot_min === 0) && 
                        bobot <= s.bobot_max
                    );
                }

                // 3. Jika masih tidak ketemu, gunakan yang pertama
                sanksi = sanksi || sanksiKategori[0];

                if (sanksi && sanksi.keputusan_tindakan && Array.isArray(sanksi.keputusan_tindakan)) {
                    sanksi.keputusan_tindakan.forEach(k => {
                        if (k) { 
                            keputusanSelect.append(`<option value="${k}">${k}</option>`);
                        }
                    });
                } else {
                    console.warn('Keputusan tindakan tidak ditemukan atau format tidak valid', sanksi);
                }
            }
        }

        // Handle old input setelah validasi gagal
        @if(old('siswa_id'))
            $('#siswa-select').val("{{ old('siswa_id') }}").trigger('change');
            setTimeout(() => {
                @if(old('jenis_id'))
                    $('#jenis-select').val("{{ old('jenis_id') }}").trigger('change');
                    setTimeout(() => {
                        @if(old('keputusan_tindakan_terpilih'))
                            $('#keputusan-select').val("{{ old('keputusan_tindakan_terpilih') }}").trigger('change');
                        @endif
                    }, 50);
                @endif
            }, 50);
        @endif
    });
</script>

@endsection