<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50">
    <!-- Navbar User -->
    <nav class="bg-white shadow-lg z-10">
        <div class="max-w-none mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Bagian kiri navbar (tanggal saja) -->
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1 text-sm font-semibold text-gray-600">
                        <span class="rounded-full"></span>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <!-- Bagian kanan navbar (nama dan avatar) -->
                <div id="user-menu-button" class="ml-3 relative flex items-center gap-2 cursor-pointer">
                    @php
                        $user = Auth::user();
                        $initial = strtoupper(substr($user->nama, 0, 1));
                    @endphp

                    <span class="text-gray-800 font-medium">{{ Auth::user()->nama }}</span>

                    <!-- Avatar dari huruf pertama nama -->
                    <div id="avatar-button" class="h-9 w-9 rounded-full bg-blue-500 text-white flex items-center justify-center text-lg font-semibold cursor-pointer">
                        {{ $initial }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Dropdown menu -->
        <div id="user-menu" class="origin-top-right absolute right-4 mt-2 w-40 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center px-4 py-2 text-red-600 hover:bg-gray-100 w-full gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: #dc2626;">
                        <path d="m10.998 16 5-4-5-4v3h-9v2h9z"></path>
                        <path d="M12.999 2.999a8.938 8.938 0 0 0-6.364 2.637L8.049 7.05c1.322-1.322 3.08-2.051 4.95-2.051s3.628.729 4.95 2.051S20 10.13 20 12s-.729 3.628-2.051 4.95-3.08 2.051-4.95 2.051-3.628-.729-4.95-2.051l-1.414 1.414c1.699 1.7 3.959 2.637 6.364 2.637s4.665-.937 6.364-2.637C21.063 16.665 22 14.405 22 12s-.937-4.665-2.637-6.364a8.938 8.938 0 0 0-6.364-2.637z"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>

        <!-- Mobile menu toggle -->
        <div class="flex items-center md:hidden px-4">
            <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" id="mobile-menu-button">
                <span class="sr-only">Open main menu</span>
                <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Script toggle -->
    <script>
        // Toggle dropdown hanya saat avatar diklik
        document.getElementById('avatar-button')?.addEventListener('click', function () {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });

        // Toggle untuk menu mobile (jika ada)
        document.getElementById('mobile-menu-button')?.addEventListener('click', function () {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>