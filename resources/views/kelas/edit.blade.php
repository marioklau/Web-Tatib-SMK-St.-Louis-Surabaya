@extends('layouts.main')

@section('title', 'Edit Data Kelas')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold">Edit Data Kelas</h1>

    <div class="bg-white p-6 shadow">
        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="kode_kelas" class="block mb-2 font-semibold text-gray-700">
                    Kode Kelas
                </label>
                <input 
                    type="text" 
                    id="kode_kelas" 
                    name="kode_kelas" 
                    value="{{ old('kode_kelas', $kelas->kode_kelas) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                @error('kode_kelas')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="nama_kelas" class="block mb-2 font-semibold text-gray-700">
                    Nama Kelas
                </label>
                <input 
                    type="text" 
                    id="nama_kelas" 
                    name="nama_kelas" 
                    value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                @error('nama_kelas')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="wali_kelas" class="block mb-2 font-semibold text-gray-700">
                    Wali Kelas
                </label>
                <input 
                    type="text" 
                    id="wali_kelas" 
                    name="wali_kelas" 
                    value="{{ old('wali_kelas', $kelas->wali_kelas) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                @error('wali_kelas')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex space-x-4 mt-6">
                <button 
                    type="submit" 
                    class="px-3 py-1.5 bg-blue-600 text-white font-semibold hover:bg-blue-700 transition duration-300"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('kelas.index') }}" 
                    class="px-3 py-2.5 bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition duration-300"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
