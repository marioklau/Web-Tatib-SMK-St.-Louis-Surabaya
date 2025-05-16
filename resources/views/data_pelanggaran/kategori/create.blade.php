@extends('layouts.main')

@section('title', 'Tambah Kategori Pelanggaran')

@section('content')
    <div class="container mx-auto mt-10 mb-10 px-10">
        <div class="grid grid-cols-1 gap-4 p-5">
            <div class="col-span-1 mt-2">
                <h1 class="text-3xl font-bold">
                    TAMBAH KATEGORI BARU
                </h1>
            </div>
        </div>
        <div class="bg-white p-5 rounded shadow-sm">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="nama_kategori" class="block mb-2 text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-full focus:outline-none focus:ring focus:border-blue-600"
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
                        class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-sm uppercase rounded-full shadow-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        Simpan
                    </button>
                    <a href="{{ route('kategori.index') }}"
                        class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-sm uppercase rounded-full shadow-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
