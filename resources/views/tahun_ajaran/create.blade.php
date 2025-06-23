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

        <div>
            <label for="status" class="block font-medium">Status</label>
            <select name="status" id="status" class="border rounded w-full px-2 py-1" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
