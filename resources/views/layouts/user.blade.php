@extends('layouts.app')

@section('content')
<div class="flex">
    <!-- Sidebar User -->
    <div class="w-56 bg-blue-600 text-white min-h-screen">
        <div class="p-4">
            <h1 class="text-xl font-bold">Menu User</h1>
            <ul class="mt-4 space-y-2">
                <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-blue-700">Dashboard</a></li>
                <li><a href="{{ route('user.pelanggaran_siswa') }}" class="block px-4 py-2 hover:bg-blue-700">Pelanggaran Siswa</a></li>
                <li><a href="{{ route('laporan.index') }}" class="block px-4 py-2 hover:bg-blue-700">Laporan</a></li>
            </ul>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="flex-1 p-8">
        @yield('user-content')
    </div>
</div>
@endsection