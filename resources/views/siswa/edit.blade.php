@extends('layouts.main')

@section('title', 'Edit Data Siswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Data Siswa</h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Pilihan kelas --}}
            <div class="mb-5">
                <label for="kelas" class="block mb-2 font-semibold text-gray-700">
                    Kelas
                </label>
                <select 
                    id="kelas" 
                    name="kelas" 
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($daftar_kelas as $kelas)
                        <option value="{{ $kelas->nama_kelas }}" {{ old('kelas', $siswa->kelas) == $kelas->nama_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} ({{ $kelas->kode_kelas }})
                        </option>
                    @endforeach
                </select>
                @error('kelas')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama siswa --}}
            <div class="mb-5">
                <label for="nama_siswa" class="block mb-2 font-semibold text-gray-700">
                    Nama Siswa
                </label>
                <input 
                    type="text" 
                    id="nama_siswa" 
                    name="nama_siswa" 
                    value="{{ old('nama_siswa', $siswa->nama_siswa) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                @error('nama_siswa')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis kelamin --}}
            <div class="mb-5">
                <label class="block mb-2 font-semibold text-gray-700">
                    Jenis Kelamin
                </label>
                <select name="jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="Laki-Laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex space-x-4 mt-6">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 transition duration-300"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('siswa.index') }}" 
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition duration-300"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
