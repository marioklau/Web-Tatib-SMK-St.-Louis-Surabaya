@extends('layouts.main')

@section('title', 'Edit Data Siswa')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Edit Data Siswa</h1>

    <div class="bg-white p-6 rounded shadow">
        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Pilih Kelas -->
            <div class="mb-4">
                <label for="kelas_id" class="block font-medium mb-1">Kelas</label>
                <select name="kelas_id" id="kelas_id" required class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($daftar_kelas as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Siswa -->
            <div class="mb-4">
                <label for="nama_siswa" class="block font-medium mb-1">Nama Siswa</label>
                <input 
                    type="text" 
                    id="nama_siswa" 
                    name="nama_siswa" 
                    value="{{ old('nama_siswa', $siswa->nama_siswa) }}" 
                    required
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-blue-500 focus:border-blue-500"
                >
                @error('nama_siswa')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIS -->
            <div class="mb-4">
                <label for="nis" class="block font-medium mb-1">NIS</label>
                <input 
                    type="text" 
                    id="nis" 
                    name="nis" 
                    value="{{ old('nis', $siswa->nis) }}" 
                    required
                    class="w-full border border-gray-300 px-3 py-2 rounded focus:ring-blue-500 focus:border-blue-500"
                >
                @error('nis')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="flex space-x-4 mt-6">
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('siswa.index') }}" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300 transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
