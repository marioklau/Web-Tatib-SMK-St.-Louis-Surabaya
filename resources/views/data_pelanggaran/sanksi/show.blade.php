@extends('layouts.main')

@section('title', 'Detail Sanksi Pelanggaran')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mx-auto mt-10 mb-10 px-10">
        <div class="grid grid-cols-8 gap-4 mb-4 p-5">
            <div class="col-span-4 mt-2">
                <h1 class="text-3xl font-bold">
                    Detail sanksi Pelanggaran: {{ $sanksi->bobot_pelanggaran }}
                </h1>
            </div>
        </div>

        <div class="bg-white p-5 rounded shadow-sm">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <tbody>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Jumlah Pelanggaran
                            </th>
                            <td class="px-6 py-4">
                                {{ $sanksi->bobot_min }} - {{ $sanksi->bobot_max }}
                            </td>
                        </tr>

                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Nama Sanksi
                            </th>
                            <td class="px-6 py-4">
                                {{ $sanksi->nama_sanksi }}
                            </td>
                        </tr>

                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Pembina
                            </th>
                            <td class="px-6 py-4">
                                {{ $sanksi->pembina }}
                            </td>
                        </tr>

                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Keputusan Tindakan
                            </th>
                            <td class="px-6 py-4">
                                {{ $sanksi->keputusan_tindakan }}
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 flex space-x-3">
            <a href="{{ route('sanksi.index') }}"
               class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs uppercase rounded-full hover:bg-gray-300 transition">
                Kembali
            </a>
        </div>
    </div>
@endsection
