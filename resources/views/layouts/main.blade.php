<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

{{-- Navbar --}}
@include('layouts.navbar')

{{-- Session Messages --}}
@if(session()->has('error'))
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded">
        {{ session('error') }}
    </div>
@endif

{{-- Content with Sidebar --}}
<div class="flex h-screen">
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 bg-gray-100 ml-64 pt-16">
        @yield('content')
    </main>
</div>

</body>
</html>