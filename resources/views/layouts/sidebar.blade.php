<!DOCTYPE html>
<html lang="en">
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 text-white">
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center gap-1">
                    <img src="{{ asset('images/logosmk.png') }}" alt="Logo" class="h-20 w-25">
                    <span class="text-3xl font-bold ml-1">Tata Tertib</span>
                </div>
            </div>
            <nav class="mt-5 px-2">
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

                    <!-- Data Pelanggaran -->
                    <div class="space-y-1">
                        <button class="w-full flex items-center justify-between px-4 py-2.5 text-5lg font-medium rounded-lg
                                       {{ request()->is('kategori*', 'jenis*', 'sanksi*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}"
                                aria-expanded="true" aria-controls="analytics-dropdown">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Data Pelanggaran
                            </div>
                            <svg class="ml-2 h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="space-y-1 pl-11" id="analytics-dropdown">
                            <a href="{{ route('kategori.index') }}"
                               class="group flex items-center px-4 py-2 text-sm rounded-md
                                      {{ request()->routeIs('kategori.index') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                                Kategori Pelanggaran
                            </a>
                            <a href="{{ route("jenis.index") }}"
                               class="group flex items-center px-4 py-2 text-sm rounded-md 
                                    {{ request()->routeIs('jenis.index') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                                Jenis Pelanggaran
                            </a>
                            <a href="{{ route('sanksi.index') }}"
                               class="group flex items-center px-4 py-2 text-sm rounded-md 
                               {{ request()->routeIs('sanksi.index') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                                Sanksi Pelanggaran
                            </a>
                        </div>
                    </div>

                    <!-- Data Kelas -->
                    <a href="#"
                       class="flex items-center px-4 py-2.5 text-5lg font-medium rounded-lg
                              {{ request()->is('kelas*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Data Kelas
                    </a>

                    <!-- Data Siswa -->
                    <a href="#"
                       class="flex items-center px-4 py-2.5 text-5lg font-medium rounded-lg
                              {{ request()->is('siswa*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Data Siswa
                    </a>

                    <!-- Pelanggaran -->
                    <a href="#"
                       class="flex items-center px-4 py-2.5 text-5lg font-medium rounded-lg
                              {{ request()->is('pelanggaran*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Input Pelanggaran
                    </a>

                    <!-- Laporan -->
                    <a href="#"
                       class="flex items-center px-4 py-2.5 text-5lg font-medium rounded-lg
                              {{ request()->is('pelanggaran*') ? 'bg-blue-700 text-white' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Laporan
                    </a>
                </div>
            </nav>
        </aside>
    </div>

    <script>
        // Dropdown toggle
        document.querySelectorAll('button[aria-controls]').forEach(button => {
            button.addEventListener('click', () => {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                const dropdownContent = document.getElementById(button.getAttribute('aria-controls'));

                button.setAttribute('aria-expanded', !isExpanded);
                dropdownContent.classList.toggle('hidden');
                button.querySelector('svg:last-child').classList.toggle('rotate-180');
            });
        });
    </script>
</body>
</html>
