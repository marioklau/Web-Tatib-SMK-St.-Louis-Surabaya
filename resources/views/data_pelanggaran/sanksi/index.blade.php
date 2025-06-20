@extends('layouts.main')

@section('title', 'Sanksi Pelanggaran')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold text-start mb-6">Sanksi Pelanggaran</h1>

    <!-- Dropdown dan Tombol Tambah Kategori -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
         <!-- Dropdown Options sebagai form -->
         <form id="filterForm" method="GET" action="{{ route('sanksi.index') }}" class="relative inline-block text-left mb-2 md:mb-0">
            <button type="button" id="menu-button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50" aria-expanded="true" aria-haspopup="true">
                {{ $filterKategori ? $kategori->firstWhere('id', $filterKategori)->nama_kategori : 'Pilih Kategori' }}
                <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>
            <input type="hidden" name="kategori_id" id="kategori_id" value="{{ $filterKategori }}">

            <div id="dropdown-menu" class="absolute left-0 z-10 mt-2 w-56 origin-top-left divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                    <a href="#" data-id="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Semua Kategori</a>
                    @foreach ($kategori as $category)
                        <a href="#" data-id="{{ $category->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                            {{ $category->nama_kategori }}
                        </a>
                    @endforeach
                </div>
            </div>
        </form>
        <!-- Tombol Tambah -->
        <a href="{{ route('sanksi.create') }}">
            <button type="button" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path>
                </svg>
                Tambah Sanksi
            </button>
        </a>
    </div>

    <!-- Tabel Kategori -->
    <div class="min-w-full bg-white border border-gray-200">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-300 text-gray-900 uppercase text-sm leading-normal">
                    <th class="py-1 px-3 border text-center">No</th>
                    <th class="py-1 px-3 border text-center">Jumlah Pelangaran</th>
                    <th class="py-1 px-3 border text-left">Pembina</th>
                    <th class="py-1 px-3 border text-left">Pembinaan</th>
                    <th class="py-1 px-3 border text-left">Keputusan Tindakan</th>
                    <th class="py-1 px-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse ($sanksi as $sanctions)
                    <tr class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="py-1 px-2 border text-center">{{ $loop->iteration }}</td>
                        <td class="py-1 px-2 border text-center">
                            @php
                                $min = $sanctions->bobot_min == 0 ? '' : $sanctions->bobot_min;
                                $max = $sanctions->bobot_max == 0 ? '' : $sanctions->bobot_max;
                            @endphp
                            {{ $min }}{{ ($min && $max) ? ' - ' : '' }}{{ $max }}
                        </td>
                        <td class="py-1 px-2 border text-left">{{ $sanctions->pembina }}</td>
                        <td class="py-1 px-2 border text-left">{!! $sanctions->nama_sanksi !!}</td>
                        <td class="py-1 px-2 border text-left">{!! $sanctions->keputusan_tindakan !!}</td>
                        <td class="py-1 px-2 border text-center">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Tombol Detail -->
                                <a href="{{ route('sanksi.show', $sanctions) }}" class="bg-green-600 text-white flex items-center gap-1 px-3 py-1 rounded-md hover:bg-green-400 transition duration-300 text-sm" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M19 3h-2c0-.55-.45-1-1-1H8c-.55 0-1 .45-1 1H5c-1.1 0-2 .9-2 2v15c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2m0 17H5V5h2v2h10V5h2z" />
                                    </svg>
                                    Detail
                                </a>

                                <!-- Tombol Edit -->
                                <a href="{{ route('sanksi.edit', $sanctions) }}" class="bg-blue-600 text-white flex items-center gap-1 px-3 py-1 rounded-md hover:bg-blue-400 transition duration-300 text-sm" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </a>

                                <!-- Tombol Delete -->
                                <form action="{{ route('sanksi.destroy', $sanctions) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sanksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white flex items-center gap-1 px-3 py-1 rounded-md hover:bg-red-400 transition duration-300 text-sm" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-3 px-6 text-center text-gray-500">Belum ada sanksi pelanggaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script untuk Dropdown -->
<script>
    document.addEventListener('click', function (event) {
        const button = document.getElementById('menu-button');
        const dropdown = document.getElementById('dropdown-menu');

        // Toggle dropdown visibility saat klik tombol
        if (button.contains(event.target)) {
            dropdown.classList.toggle('hidden');
        } else {
            dropdown.classList.add('hidden');
        }

        // Jika klik di salah satu kategori
        if (event.target.closest('#dropdown-menu a')) {
            event.preventDefault();
            const selected = event.target.closest('a');
            const kategoriId = selected.getAttribute('data-id');
            const kategoriName = selected.textContent.trim();

            // Set nilai input hidden
            document.getElementById('kategori_id').value = kategoriId;

            // Update button text
            button.innerHTML = kategoriName + ' <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>';

            // Submit form untuk reload halaman dengan filter
            document.getElementById('filterForm').submit();
        }
    });
</script>
@endsection
