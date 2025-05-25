@extends('layouts.main')

@section('title', 'Import Data Siswa')

@section('content')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<div class="container mx-auto mt-5 mb-5 px-5">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-3xl font-bold mb-6">Import Data Siswa</h3>

        <!-- Form Import -->
        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4 mb-6">
            @csrf
            <input 
                type="file" 
                name="file" 
                accept=".csv, .xlsx" 
                required 
                class="border border-gray-300 rounded px-4 py-2 w-full md:w-auto"
            >

            <button 
                type="submit" 
                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
                Import
            </button>
        </form>

        <!-- Tampilkan Error Jika Ada -->
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tombol Kembali -->
        <a href="{{ route('siswa.index') }}"
           class="inline-block px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            Kembali
        </a>
    </div>
</div>
@endsection
