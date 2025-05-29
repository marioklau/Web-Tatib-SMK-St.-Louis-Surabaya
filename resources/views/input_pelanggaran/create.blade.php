@extends('layouts.main')

@section('title', 'Input Pelanggaran')

@section('content')
<div class="container mx-auto mt-10 mb-10 px-10">
    <div class="grid grid-cols-1 gap-4 p-5">
        <div class="col-span-1 mt-2">
            <h1 class="text-3xl font-bold">
                INPUT PELANGGARAN
            </h1>
        </div>
    </div>
    <div class="bg-white p-5 rounded shadow-sm">
        <form action="{{ route('pelanggaran.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="siswa_id" class="block mb-2 text-sm font-medium text-gray-700">Nama Siswa</label>
                <select name="siswa_id" id="siswa_id" class="block w-full px-3 py-2 border rounded-full" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }}
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')
                    <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="kategori_id" class="block mb-2 text-sm font-medium text-gray-700">Kategori Pelanggaran</label>
                <select name="kategori_id" id="kategori_id" class="block w-full px-3 py-2 border rounded-full" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="jenis_id" class="block mb-2 text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
                <select name="jenis_id" id="jenis_id" class="block w-full px-3 py-2 border rounded-full" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($jenis as $j)
                        <option value="{{ $j->id }}" {{ old('jenis_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jenis }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_id')
                    <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="sanksi_id" class="block mb-2 text-sm font-medium text-gray-700">Sanksi</label>
                <select name="sanksi_id" id="sanksi_id" class="block w-full px-3 py-2 border rounded-full" required>
                    <option value="">-- Pilih Sanksi --</option>
                    @foreach($sanksi as $s)
                        <option value="{{ $s->id }}" {{ old('sanksi_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_sanksi }}
                        </option>
                    @endforeach
                </select>
                @error('sanksi_id')
                    <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-3">
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                    Simpan
                </button>
                <a href="{{ route('pelanggaran.index') }}"
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection