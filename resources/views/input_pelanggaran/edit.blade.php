@extends('layouts.main')

@section('title', 'Edit Pelanggaran Siswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Pelanggaran</h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('pelanggaran.update', $pelanggaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="siswa_id" class="block mb-2 font-semibold text-gray-700">Nama Siswa</label>
                <select name="siswa_id" id="siswa_id" required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ $pelanggaran->siswa_id == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }} ({{ $s->kelas->nama_kelas }})
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="kategori_id" class="block mb-2 font-semibold text-gray-700">Kategori Pelanggaran</label>
                <select name="kategori_id" id="kategori_id" required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}" {{ $pelanggaran->kategori_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="jenis_pelanggaran_id" class="block mb-2 font-semibold text-gray-700">Jenis Pelanggaran</label>
                <select name="jenis_pelanggaran_id" id="jenis_pelanggaran_id" required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                    @foreach($jenis as $j)
                        <option value="{{ $j->id }}" {{ $pelanggaran->jenis_pelanggaran_id == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jenis }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_pelanggaran_id')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="sanksi_id" class="block mb-2 font-semibold text-gray-700">Sanksi</label>
                <select name="sanksi_id" id="sanksi_id" required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Sanksi --</option>
                    @foreach($sanksi as $s)
                        <option value="{{ $s->id }}" {{ $pelanggaran->sanksi_id == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_sanksi }}
                        </option>
                    @endforeach
                </select>
                @error('sanksi_id')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 transition duration-300">
                    Simpan Perubahan
                </button>
                <a href="{{ route('pelanggaran.index') }}"
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition duration-300">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection