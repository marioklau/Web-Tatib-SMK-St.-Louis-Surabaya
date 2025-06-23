@extends('layouts.main')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Tambah Tahun Ajaran Baru</h1>

    <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="tahun_ajaran" class="block font-medium">Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" id="tahun_ajaran" class="border rounded w-full px-2 py-1" placeholder="Contoh: 2024/2025" required>
        </div>

        <div class="flex space-x-4 mt-6">
            <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white font-semibold hover:bg-blue-700 transition duration-300">Simpan</button>
                <a href="{{ route('tahun-ajaran.index') }}"
                    class="px-3 py-1.5 bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition duration-300">
                    Kembali
                </a>
        </div>
    </form>
</div>
@endsection
