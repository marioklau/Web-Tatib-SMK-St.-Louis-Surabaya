@extends('layouts.main')

@section('title', 'Edit Sanksi Pelanggaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold">Edit Sanksi Pelanggaran</h1>

    <div class="bg-white p-6 shadow">
    <form method="POST" action="{{ route('sanksi.update', $sanksi->id) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="kategori_id" class="block mb-2">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ $sanksi->kategori_id == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="bobot_min" class="block mb-2">Bobot Minimal</label>
                <input type="number" name="bobot_min" id="bobot_min" 
                    value="{{ old('bobot_min', $sanksi->bobot_min) }}" 
                    class="w-full p-2 border rounded" min="0">
            </div>
            <div>
                <label for="bobot_max" class="block mb-2">Bobot Maksimal</label>
                <input type="number" name="bobot_max" id="bobot_max" 
                    value="{{ old('bobot_max', $sanksi->bobot_max) }}" 
                    class="w-full p-2 border rounded" min="0" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="nama_sanksi" class="block mb-2">Nama Sanksi (pisahkan dengan enter)</label>
            <textarea name="nama_sanksi" id="nama_sanksi" rows="4" 
                    class="w-full p-2 border rounded" required>@if(is_array($sanksi->nama_sanksi)){{ implode("\n", $sanksi->nama_sanksi) }}@else{{ $sanksi->nama_sanksi }}@endif</textarea>
        </div>

        <div class="mb-4">
            <label for="pembina" class="block mb-2">Pembina</label>
            <input type="text" name="pembina" id="pembina" 
                value="{{ old('pembina', $sanksi->pembina) }}" 
                class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="keputusan_tindakan" class="block mb-2">Keputusan Tindakan (pisahkan dengan enter)</label>
            <textarea name="keputusan_tindakan" id="keputusan_tindakan" rows="4" 
                    class="w-full p-2 border rounded" required>@if(is_array($sanksi->keputusan_tindakan)){{ implode("\n", $sanksi->keputusan_tindakan) }}@else{{ $sanksi->keputusan_tindakan }}@endif</textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('sanksi.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
    </div>
</div>
@endsection