<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pastikan content area menghitung tinggi navbar dan sidebar dengan benar */
        .main-content {
            margin-left: 16rem; /* Sesuaikan dengan lebar sidebar */
            padding-top: 4rem; /* Sesuaikan dengan tinggi navbar */
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Sidebar Fixed -->
<div class="fixed inset-y-0 left-0 w-64 bg-white shadow z-20">
    @include('layouts.sidebar')
</div>

<!-- Navbar Fixed -->
<nav class="fixed top-0 left-64 right-0 h-16 bg-white shadow z-10">
    @include('layouts.navbar')
</nav>

<!-- Main Content Area -->
<main class="main-content">
    <!-- Session Messages -->
    @if(session()->has('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded m-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Dynamic Content -->
    <div class="p-6">
        @yield('content')
    </div>
</main>
@yield('scripts')
</body>
</html>
