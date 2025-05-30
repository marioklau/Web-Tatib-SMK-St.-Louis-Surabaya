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
        <div>
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
                @foreach($siswa as $s)
                    <option value="{{ $s->id }}">{{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                @endforeach
            </datalist> -->
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- buat nama dulu e -->
            <!-- <div>
                <label class="block mb-1">Nama Siswa</label>
                <select name="siswa_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div> -->

            <div>
                <label class="block mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border p-2 rounded" required id="kategori-select">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Jenis Pelanggaran</label>
                <select name="jenis_id" class="w-full border p-2 rounded" required id="jenis-select">
                    <option value="">-- Pilih Jenis --</option>
                </select>
            </div>

            <div>
                <label class="block mb-1">Sanksi</label>
                <select name="sanksi_id" class="w-full border p-2 rounded" required id="sanksi-select">
                    <option value="">-- Pilih Sanksi --</option>
                    @foreach($sanksi as $s)
                        <option data-kategori="{{ $s->kategori_id }}" value="{{ $s->id }}">{{ $s->nama_sanksi }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <button type="button" onclick="history.back()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Kembali</button>
        </div>
    </form>
</div>

<script>
    const kategoriSelect = document.getElementById('kategori-select');
    const jenisSelect = document.getElementById('jenis-select');
    const sanksiSelect = document.getElementById('sanksi-select');
    const kategoriJenis = @json($kategori);
    const siswaList = @json($siswa); // Sudah termasuk relasi kelas

    siswaList.forEach(s => {
        document.getElementById('siswa-select').innerHTML += 
            `<option value="${s.id}">${s.nama_siswa}</option>`;
    });

    // pakai select2 untuk search bar
    $(document).ready(function() {
        $('#siswa-select').select2({
            placeholder: "-- Pilih Siswa --",
            allowClear: true,
            minimumInputLength: 1
        });
    });

    kategoriSelect.addEventListener('change', function () {
        const kategoriId = this.value;

        jenisSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
        kategoriJenis.forEach(k => {
            if (k.id == kategoriId) {
                console.log(k.jenis);
                // k.jenis.forEach(j => {
                //     // j.nama_jenis diganti jadi j.bentuk_pelanggaran, menyesuaikan migrasi tabel Jenis
                //     jenisSelect.innerHTML += `<option value="${j.id}">${j.bentuk_pelanggaran}</option>`;
                // });
                if (k.jenis && Array.isArray(k.jenis)) {
                    k.jenis.forEach(j => {
                        jenisSelect.innerHTML += `<option value="${j.id}">${j.bentuk_pelanggaran}</option>`;
                    });
                }
            }
        });

        Array.from(sanksiSelect.options).forEach(opt => {
            if (opt.dataset.kategori && opt.dataset.kategori !== kategoriId) {
                opt.style.display = 'none';
            } else {
                opt.style.display = '';
            }
        });
    });
</script>
@endsection
