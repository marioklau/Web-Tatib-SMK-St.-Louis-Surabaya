@extends('layouts.main')

@section('title', 'Edit Jenis Pelanggaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Bentuk Pelanggaran</h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('jenis.update', ['jenis' => $jenis->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="nama_kategori" class="block mb-2 font-semibold text-gray-700">
                    Nama Bentuk Pelanggaran
                </label>
                <input 
                    type="text" 
                    id="bentuk_pelanggaran" 
                    name="bentuk_pelanggaran" 
                    value="{{ old('bentuk_pelanggaran', $jenis->bentuk_pelanggaran) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                @error('bentuk_pelanggaran')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4 mt-6">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 transition duration-300"
                >
                    Simpan Perubahan
                </button>
                <a 
                    href="{{ route('jenis.index') }}" 
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition duration-300"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
