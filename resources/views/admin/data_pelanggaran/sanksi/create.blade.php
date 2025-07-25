@extends('layouts.main')

@section('title', 'Tambah Sanksi Pelanggaran')
@section('content')
    <div class="container mx-auto">
        <div class="grid grid-cols-1">
            <div class="col-span-1">
                <h1 class="text-2xl font-semibold mb-2">
                    Tambah Sanksi Baru
                </h1>
            </div>
        </div>
        <div class="bg-white p-5 rounded shadow-sm">
            <form action="{{ route('sanksi.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <!-- Dropdown Kategori -->
                    <label for="kategori_id" class="block mb-2 text-sm font-medium text-gray-700">Kategori Pelanggaran</label>
                    <select id="kategori_id" name="kategori_id"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-7">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bobot_min" class="block mb-2 text-sm font-medium text-gray-700">Jumlah Pelanggaran Minimum</label>
                            <input type="number" id="bobot_min" name="bobot_min" min="0"
                                class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                                value="{{ old('bobot_min') }}"
                                placeholder="Kosongkan jika tidak ada minimum">
                            @error('bobot_min')
                                <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="bobot_max" class="block mb-2 text-sm font-medium text-gray-700">Jumlah Pelanggaran Maksimum</label>
                            <input type="number" id="bobot_max" name="bobot_max" min="0"
                                class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                                value="{{ old('bobot_max') }}" 
                                required>
                            @error('bobot_max')
                                <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <label for="pembina" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Pembina</label>
                    <input type="text" id="pembina" name="pembina"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded-none focus:outline-none focus:ring focus:border-blue-600"
                        value="{{ old('pembina') }}" required>
                    @error('pembina')
                        <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                    @enderror

                    <label for="nama_sanksi" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Pembinaan (Pisahkan dengan baris baru)</label>
                    <textarea id="nama_sanksi" name="nama_sanksi" rows="5"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-600"
                        required>{{ is_array(old('nama_sanksi')) ? implode("\n", old('nama_sanksi')) : old('nama_sanksi') }}</textarea>
                    @error('nama_sanksi')
                        <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                    @enderror

                    <label for="keputusan_tindakan" class="block mb-2 text-sm font-medium text-gray-700 mt-4">Keputusan Tindakan (Pisahkan dengan baris baru)</label>
                    <textarea id="keputusan_tindakan" name="keputusan_tindakan" rows="5"
                        class="block w-full px-3 py-2 text-base text-gray-700 bg-white border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-600"
                        required>{{ is_array(old('keputusan_tindakan')) ? implode("\n", old('keputusan_tindakan')) : old('keputusan_tindakan') }}</textarea>
                    @error('keputusan_tindakan')
                        <div class="bg-red-400 text-white p-2 rounded mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-3 flex gap-2">
                    <button type="submit"
                        class="inline-block px-4 py-2 bg-blue-600 text-white font-medium text-sm uppercase rounded shadow-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        Simpan
                    </button>
                    <a href="{{ route('sanksi.index') }}"
                        class="inline-block px-4 py-2 bg-gray-200 text-gray-700 font-medium text-sm uppercase rounded shadow-md hover:bg-gray-300 transition duration-150 ease-in-out">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Client-side validation untuk bobot_max > bobot_min
        document.querySelector('form').addEventListener('submit', function(e) {
            const bobotMin = document.getElementById('bobot_min').value;
            const bobotMax = document.getElementById('bobot_max').value;
            
            if (bobotMin && bobotMax && parseInt(bobotMax) <= parseInt(bobotMin)) {
                e.preventDefault();
                alert('Bobot maksimum harus lebih besar dari bobot minimum');
                document.getElementById('bobot_max').focus();
            }
        });
    </script>
@endsection