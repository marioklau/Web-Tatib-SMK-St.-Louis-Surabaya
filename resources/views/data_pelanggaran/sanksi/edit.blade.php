@extends('layouts.main')

@section('title', 'Edit Sanksi Pelanggaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Sanksi Pelanggaran</h1>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('sanksi.update', ['sanksi' => $sanksi->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="bobot_min" class="block mb-2 font-semibold text-gray-700">
                    Jumlah Pelanggaran Minimum
                </label>
                <input 
                    type="text" 
                    id="bobot_min" 
                    name="bobot_min" 
                    value="{{ old('bobot_min', $sanksi->bobot_min) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bobot_min')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="bobot_max" class="block mb-2 font-semibold text-gray-700">
                    Jumlah Pelanggaran Maksimum
                </label>
                <input 
                    type="text" 
                    id="bobot_max" 
                    name="bobot_max" 
                    value="{{ old('bobot_max', $sanksi->bobot_max) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bobot_max')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="bobot_pelanggaran" class="block mb-2 font-semibold text-gray-700">
                    Nama Sanksi
                </label>
                <input  
                    type="text" 
                    id="nama_sanksi" 
                    name="nama_sanksi" 
                    value="{{ old('nama_sanksi', $sanksi->nama_sanksi) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('nama_sanksi')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="bobot_pelanggaran" class="block mb-2 font-semibold text-gray-700">
                    Pembina
                </label>
                <input  
                    type="text" 
                    id="pembina" 
                    name="pembina" 
                    value="{{ old('pembina', $sanksi->pembina) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('pembina')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="bobot_pelanggaran" class="block mb-2 font-semibold text-gray-700">
                    Keputusan Tindakan
                </label>
                <input  
                    type="text" 
                    id="keputusan_tindakan" 
                    name="keputusan_tindakan" 
                    value="{{ old('keputusan_tindakan', $sanksi->keputusan_tindakan) }}" 
                    required
                    class="block w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('keputusan_tindakan')
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
                    href="{{ route('sanksi.index') }}" 
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition duration-300"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
