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
        <div class="grid">
            <label class="block mb-1">Nama Siswa</label>
            <select name="siswa_id" class="w-full border p-2 rounded" id="siswa-select" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach ($siswa as $s)
                    <!-- <option value="{{ $s->id }}">{{ $s->nama }} - {{ $s->kelas->nama_kelas }}</option> -->
                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                @endforeach
            </select>
            <!-- <input list="siswa-list" name="siswa_id" class="w-full border p-2 rounded" placeholder="Cari nama siswa..." required>
            <datalist id="siswa-list">
                {{-- @foreach($siswa as $s)
                    <option value="{{ $s->id }}">{{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                @endforeach --}}
            </datalist> -->
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2"> 
            <!-- buat nama dulu e -->
            <!-- <div>
                <label class="block mb-1">Nama Siswa</label>
                <select name="siswa_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Pilih Siswa --</option>
                    {{-- @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach --}}
                </select>
            </div> -->

            {{-- Kategori old, gk jd dipakek --}}
            {{-- <div>
                <label class="block mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border p-2 rounded" required id="kategori-select">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div> --}}

            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                {{-- <select name="jenis_id" class="w-full border p-2 rounded" required id="jenis-select">
                    <option value="">-- Pilih Jenis --</option>
                </select> --}}
                <select name="jenis_id" class="w-full border p-2 rounded" id="jenis-select" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach ($jenis as $j)
                    <option value="{{ $j->id }}">{{ $j->bentuk_pelanggaran }}</option>
                @endforeach
            </select>
            </div>

            <!-- Kategori Pelanggaran -->
            <div>
                <label class="block mb-1">Kategori</label>
                <input type="text" id="kategori-display" class="w-full border p-2 rounded bg-gray-100 text-gray-600" readonly>
                <!-- Untuk dikirim ke DB -->
                <input type="hidden" name="kategori_id" id="kategori-id">
            </div>

            <div>
                <label class="block mb-1">Sanksi</label>
                <select name="sanksi_id" class="w-full border p-1 rounded" required id="sanksi-select">
                    <option value="">-- Pilih Sanksi --</option>
                    @foreach($sanksi as $s)
                        <option data-kategori="{{ $s->kategori_id }}" value="{{ $s->id }}">{{ $s->nama_sanksi }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <button type="button" onclick="history.back()" class="inline-block px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Kembali</button>
        </div>
    </form>
</div>

<script>
    const kategoriSelect = document.getElementById('kategori-select');
    const jenisSelect = document.getElementById('jenis-select');
    const sanksiSelect = document.getElementById('sanksi-select');
    const siswaList = @json($siswa); // Sudah termasuk relasi kelas
    const jenisList = @json($jenis);

    siswaList.forEach(s => {
        document.getElementById('siswa-select').innerHTML += 
            `<option value="${s.id}">${s.nama_siswa}</option>`;
    });

    // pakai select2 untuk search bar, reusable
    $(document).ready(function() {
        $('select').select2({
            placeholder: "-- Pilih --",
            allowClear: true,
            minimumInputLength: 1
        }).on('select2:open', function () {
            setTimeout(() => {
                document.querySelector('.select2-container--open .select2-search__field').focus();
            }, 100);
        });
    });

    // Event: saat jenis dipilih
    $('#jenis-select').on('change', function () {
        const selectedOption = $(this).find('option:selected');
        const kategoriName = selectedOption.data('kategori');
        const jenisId = $(this).val();
        const selectedJenis = jenisList.find(j => j.id == jenisId);

        // Set nilai input read-only dan hidden input
        $('#kategori-display').val(kategoriName || '-');

        if (selectedJenis && selectedJenis.kategori) {
            $('#kategori-display').val(selectedJenis.kategori.nama_kategori);
            $('#kategori-id').val(selectedJenis.kategori.id);
        } else {
            $('#kategori-display').val('-');
            $('#kategori-id').val('');
        }
    });

    // kategoriSelect.addEventListener('change', function () {
    //     const kategoriId = this.value;

    //     jenisSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
    //     kategoriJenis.forEach(k => {
    //         if (k.id == kategoriId) {
    //             console.log(k.jenis);
    //             // k.jenis.forEach(j => {
    //             //     // j.nama_jenis diganti jadi j.bentuk_pelanggaran, menyesuaikan migrasi tabel Jenis
    //             //     jenisSelect.innerHTML += `<option value="${j.id}">${j.bentuk_pelanggaran}</option>`;
    //             // });
    //             if (k.jenis && Array.isArray(k.jenis)) {
    //                 k.jenis.forEach(j => {
    //                     jenisSelect.innerHTML += `<option value="${j.id}">${j.bentuk_pelanggaran}</option>`;
    //                 });
    //             }
    //         }
    //     });

    //     Array.from(sanksiSelect.options).forEach(opt => {
    //         if (opt.dataset.kategori && opt.dataset.kategori !== kategoriId) {
    //             opt.style.display = 'none';
    //         } else {
    //             opt.style.display = '';
    //         }
    //     });
    // });
</script>
@endsection
