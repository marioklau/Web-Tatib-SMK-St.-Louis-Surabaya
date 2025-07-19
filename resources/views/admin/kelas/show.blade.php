@extends('layouts.main')

@section('title', 'Detail Kelas')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mx-auto">
        <div class="grid grid-cols-8 mb-2">
            <div class="col-span-4">
                <h1 class="text-2xl font-semibold">
                    Detail Kelas
                </h1>
            </div>
        </div>

        <div class="bg-white p-5 rounded shadow-sm">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 flex space-x-3">
            <a href="{{ route('kelas.index') }}"
            class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs uppercase hover:bg-gray-300 transition">
                Kembali
            </a>

            <a href="{{ route('kelas.edit', $kelas) }}"
               class="px-6 py-2.5 bg-blue-400 text-white font-medium text-xs uppercase hover:bg-blue-500 transition">
                Edit Kategori
            </a>
        </div>
    </div>
@endsection
