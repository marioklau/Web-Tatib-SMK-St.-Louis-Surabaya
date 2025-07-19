@extends('layouts.main')

@section('title', 'Detail Kategori Pelanggaran')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mx-auto mt-10 mb-10 px-10">
        <div class="grid grid-cols-8 gap-4 mb-4 p-5">
            <div class="col-span-4 mt-2">
                <h1 class="text-3xl font-bold">
                    Detail Kategori Pelanggaran: {{ $categories->nama_kategori }}
                </h1>
            </div>
        </div>

        <div class="bg-white p-5 rounded shadow-sm">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <tbody>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Nama Kategori
                            </th>
                            <td class="px-6 py-4">
                                {{ $categories->nama_kategori }}
                            </td>
                        </tr>
                        @if(isset($categories->jumlah))
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Jumlah
                            </th>
                            <td class="px-6 py-4">
                                {{ $categories->jumlah }}
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 flex space-x-3">
            <a href="{{ route('kategori.index') }}"
               class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs uppercase rounded-full hover:bg-gray-300 transition">
                Back
            </a>

            <a href="{{ route('kategori.edit', $categories) }}"
               class="px-6 py-2.5 bg-blue-400 text-white font-medium text-xs uppercase rounded-full hover:bg-blue-500 transition">
                Edit Kategori
            </a>
        </div>
    </div>
@endsection
