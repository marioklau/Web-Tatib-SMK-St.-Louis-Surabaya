@extends('layouts.main')

@section('title', 'Edit Sanksi Pelanggaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold">Edit Sanksi Pelanggaran</h1>

    <div class="bg-white p-6 shadow">
        <form action="{{ route('sanksi.update', ['sanksi' => $sanksi->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="bobot_min" class="block mb-2 font-semibold text-gray-700">
                    Jumlah Pelanggaran Minimum
                </label>
                <input
                    type="number" {{-- Ubah type="text" ke type="number" lebih baik untuk bobot --}}
                    id="bobot_min"
                    name="bobot_min"
                    value="{{ old('bobot_min', $sanksi->bobot_min) }}"
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bobot_min')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="bobot_max" class="block mb-2 font-semibold text-gray-700">
                    Jumlah Pelanggaran Maksimum
                </label>
                <input
                    type="number" {{-- Ubah type="text" ke type="number" lebih baik untuk bobot --}}
                    id="bobot_max"
                    name="bobot_max"
                    value="{{ old('bobot_max', $sanksi->bobot_max) }}"
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('bobot_max')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="nama_sanksi" class="block mb-2 font-semibold text-gray-700">
                    Pembinaan (Pisahkan dengan baris baru)
                </label>
                <textarea
                    id="nama_sanksi"
                    name="nama_sanksi"
                    rows="5" {{-- Tambahkan rows untuk ukuran textarea --}}
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >{{ old('nama_sanksi', is_array($sanksi->nama_sanksi) ? implode("\n", $sanksi->nama_sanksi) : $sanksi->nama_sanksi) }}</textarea>
                @error('nama_sanksi')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="pembina" class="block mb-2 font-semibold text-gray-700">
                    Pembina
                </label>
                <input
                    type="text"
                    id="pembina"
                    name="pembina"
                    value="{{ old('pembina', $sanksi->pembina) }}"
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('pembina')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="keputusan_tindakan" class="block mb-2 font-semibold text-gray-700">
                    Keputusan Tindakan (Pisahkan dengan baris baru)
                </label>
                <textarea
                    id="keputusan_tindakan"
                    name="keputusan_tindakan"
                    rows="5" {{-- Tambahkan rows untuk ukuran textarea --}}
                    required
                    class="block w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >{{ old('keputusan_tindakan', is_array($sanksi->keputusan_tindakan) ? implode("\n", $sanksi->keputusan_tindakan) : $sanksi->keputusan_tindakan) }}</textarea>
                @error('keputusan_tindakan')
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
                    href="{{ route('sanksi.index') }}"
                    class="px-3 py-1.5 bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition duration-300"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection