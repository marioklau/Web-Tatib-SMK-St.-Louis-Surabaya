<!DOCTYPE html>
<html lang="en">
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white">
            <div class="p-3">
                <div class="flex items-center gap-1">
                    <img src="{{ asset('images/logosmk.png') }}" alt="Logo" class="h-10 w-15">
                    <span class="text-2xl font-semibold ml-1">Tata Tertib</span>
                </div>
            </div>
            <nav class="mt-3 px-2">
                <!-- Main Navigation -->
                <div class="space-y-4">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center px-4 py-2.5 text-5lg font-medium rounded-lg
                              {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <!-- Pelanggaran Hari Ini -->
                    <a href="{{ route('user.pelanggaran_siswa') }}"
                    class="flex items-center px-4 py-2.5 text-5lg font-medium rounded-lg
                            {{ request()->routeIs('user.pelanggaran_siswa') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Pelanggaran Siswa
                    </a>
                </div>
            </nav>
        </aside>
    </div>
</body>
</html>