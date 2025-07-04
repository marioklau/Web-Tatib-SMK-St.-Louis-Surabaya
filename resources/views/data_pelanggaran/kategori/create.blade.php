@extends('layouts.main')

@section('title', 'Tambah Kategori Pelanggaran')

@section('content')
    <div class="container mx-auto">
        <div class="grid grid-cols-1 ">
            <div class="col-span-1">
                <h1 class="text-2xl font-semibold">
                    Tambah Kategori Baru
                </h1>
            </div>
        </div>
        <div class="bg-white p-5 shadow-sm">
            <form action="{{ route('kategori.store') }}" method="POST">
                 @csrf

                <div class="mb-5">
                    <label for="nama_kategori" class="block mb-2 text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                        value="{{ old('nama_kategori') }}" required>

                    <!-- error message -->
                    @error('nama_kategori')
                        <div class="bg-red-400 text-white p-2 rounded mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit"
                        class="inline-block px-3 py-1.5 bg-blue-600 text-white font-medium text-sm uppercase shadow-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        Simpan
                    </button>
                    <a href="{{ route('kategori.index') }}"
                        class="inline-block px-3 py-1.5 bg-gray-200 text-gray-700 font-medium text-sm uppercase shadow-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
