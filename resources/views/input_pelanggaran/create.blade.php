@extends('layouts.main')

@section('title', 'Form Input Pelanggaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Form Input Pelanggaran</h1>

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
            <input list="siswa-list" name="siswa_id" class="w-full border p-2 rounded" placeholder="Cari nama siswa..." required>
            <datalist id="siswa-list">
                @foreach($siswa as $s)
                    <option value="{{ $s->id }}">{{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                @endforeach
            </datalist>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
        <div class="mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>

<script>
    const kategoriSelect = document.getElementById('kategori-select');
    const jenisSelect = document.getElementById('jenis-select');
    const sanksiSelect = document.getElementById('sanksi-select');
    const kategoriJenis = @json($kategori);

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
