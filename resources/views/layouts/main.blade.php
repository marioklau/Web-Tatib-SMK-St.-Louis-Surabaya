<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Ensure content is not obscured by navbar/sidebar */
        .main-content {
            margin-left: 16rem; /* Adjust to sidebar width */
            padding-top: 4rem;  /* Adjust to navbar height */
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="fixed inset-y-0 left-0 w-64 bg-white shadow z-20">
    @include('layouts.sidebar')
</div>

<nav class="fixed top-0 left-64 right-0 h-16 bg-white shadow z-10">
    @include('layouts.navbar')
</nav>

<main class="main-content">
    @if(session()->has('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded m-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="p-6">
        @yield('content')
    </div>
</main>

@yield('scripts')

</body>
</html>