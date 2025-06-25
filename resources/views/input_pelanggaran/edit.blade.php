@extends('layouts.main')

@section('title', 'Edit Pelanggaran')

@section('content')

<!-- CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery dan JS Select2 -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-6">Edit Pelanggaran</h1>

    <form action="{{ route('input-pelanggaran.update', $pelanggaran->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Pilih Siswa -->
        <div>
            <label for="siswa_id">Siswa</label>
                <select name="siswa_id" class="form-control">
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ $pelanggaran->siswa_id == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_siswa }}
                        </option>
                    @endforeach
                </select>
        </div>

        <!-- Pilih Kategori -->
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Kategori</label>
            <input type="text" id="kategori-display" 
                class="w-full border p-2 rounded bg-gray-100 text-gray-600" 
                value="{{ $pelanggaran->kategori->nama_kategori }}" 
                readonly>

            <input type="hidden" name="kategori_id" id="kategori-id" value="{{ $pelanggaran->kategori_id }}">
        </div>


        <!-- Pilih Jenis -->
        <div>
            <label for="jenis_id" class="block text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
            <select name="jenis_id" id="jenis-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- Pilih Jenis Pelanggaran --</option>
                @foreach($jenis as $j)
                    <option 
                        value="{{ $j->id }}"
                        data-kategori="{{ $j->kategori_id }}"
                        data-kategori-nama="{{ $j->kategori->nama_kategori }}"
                        {{ $pelanggaran->jenis_id == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_jenis }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Pilih Sanksi -->
        <div>
            <label for="sanksi_id" class="block text-sm font-medium text-gray-700">Sanksi</label>
            <select name="sanksi_id" id="sanksi-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach($sanksi as $s)
                    <option value="{{ $s->id }}" data-kategori="{{ $s->kategori_id }}"
                        {{ $pelanggaran->sanksi_id == $s->id ? 'selected' : '' }}>
                        {{ $s->keputusan_tindakan }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
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
    const kategoriSelect = document.getElementById('kategori-select');
    const jenisSelect = document.getElementById('jenis-select');
    const sanksiSelect = document.getElementById('sanksi-select');
    const kategoriJenis = @json($kategori);

    kategoriSelect.addEventListener('change', function () {
        const kategoriId = this.value;

        jenisSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
        kategoriJenis.forEach(kat => {
            if (kat.id == kategoriId) {
                kat.jenis.forEach(j => {
                    jenisSelect.innerHTML += `<option value="${j.id}">${j.bentuk_pelanggaran}</option>`;
                });
            }
        });

        Array.from(sanksiSelect.options).forEach(opt => {
            opt.style.display = opt.dataset.kategori == kategoriId || !opt.dataset.kategori ? '' : 'none';
        });
    });
</script>
@endsection
