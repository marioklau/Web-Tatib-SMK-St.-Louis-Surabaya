@extends('layouts.main')

@section('title', 'Detail Siswa')

@section('content')
<section class="mb-2 border bg-white shadow p-4 rounded-lg max-w-full">
    <div class="mx-auto">
        <div class="card md:flex max-w-lg">
            <div class="w-20 h-20 mx-auto mb-6 md:mr-6 flex-shrink-0">
                <img class="object-cover rounded-full" src="https://tailwindflex.com/public/images/user.png">
            </div>
            <div class="flex-grow text-center md:text-left">
                @if ($siswa)
                    <p class="text-xl heading">{{ $siswa->nama_siswa }}</p>
                    <p class="">{{ $siswa->kelas->nama_kelas }}</p>
                    <p class="">{{ $siswa->nis }}</p>
                    <p class="">{{ $siswa->jenis_kelamin }}</p>
                @else
                    <p class="text-gray-500">Data siswa tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</section>
    <div class="mt-3">
        <a href="{{ route('siswa.index') }}"
        class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium text-xs uppercase rounded-full hover:bg-gray-300 transition">
              Kembali
        </a>
    </div>
@endsection
