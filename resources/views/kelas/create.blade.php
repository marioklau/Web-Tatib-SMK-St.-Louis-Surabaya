@extends('layouts.main')

@section('title', 'Tambah Data Kelas')
@section('content')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <div class="container mx-auto ">
        <div class="grid grid-cols-1">
            <div class="col-span-1">
                <h1 class="text-2xl font-semibold">
                    Tambah Kelas Baru
                </h1>
            </div>
        </div>
        <div class="bg-white p-5 rounded shadow-sm">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf

                <div class="mb-7">

                    <label for="kode_kelas" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Kode Kelas</label>
                    <input type="text" id="kode_kelas" name="kode_kelas"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                        value="{{ old('kode_kelas') }}" required>

                    <label for="nama_kelas" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Nama Kelas</label>
                    <input type="text" id="nama_kelas" name="nama_kelas"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                        value="{{ old('nama_kelas') }}" required>

                    <label for="wali_kelas" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Wali Kelas</label>
                    <input type="text" id="wali_kelas" name="wali_kelas"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                        value="{{ old('wali_kelas') }}" required>

                    <!-- error messages -->
                    @error('bobot_min')
                        <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                    @enderror
                    @error('bobot_max')
                        <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit"
                        class="inline-block px-3 py-1.5 bg-blue-600 text-white font-medium text-sm uppercase shadow-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        Simpan
                    </button>
                    <a href="{{ route('kelas.index') }}"
                        class="inline-block px-3 py-1.5 bg-gray-200 text-gray-700 font-medium text-sm uppercase shadow-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection


