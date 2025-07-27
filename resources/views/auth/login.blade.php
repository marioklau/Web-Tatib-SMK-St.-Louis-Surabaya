<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Web Tatib SMKK St. Louis</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl bg-white rounded-lg shadow-lg flex flex-col md:flex-row overflow-hidden">
        
        <!-- Left: Info + Logo -->
        <div class="w-full md:w-1/2 bg-blue-800 text-white p-8 md:p-10 flex flex-col justify-center items-center space-y-6">
            <!-- LOGO -->
            <div class="flex justify-center">
                <img src="{{ asset('images/logosmk.png') }}" alt="Logo SMKK St. Louis" class="h-20">
            </div>
            
            <div class="text-center">
                <h1 class="text-2xl md:text-3xl font-bold">Web Tatib SMKK St. Louis</h1>
                <p class="mt-2 opacity-90">Sistem Tata Tertib Siswa</p>
            </div>
        </div>

        <!-- Right: Form Login -->
        <div class="w-full md:w-1/2 p-8 md:p-10">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Selamat Datang</h2>

            {{-- Notifikasi --}}
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if (session()->has('loginError'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('loginError') }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nama Pengguna</label>
                    <div class="flex items-center border border-gray-300 rounded-md overflow-hidden transition-all duration-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                        <span class="bg-gray-100 text-gray-500 px-3 py-2">
                            <i class="fas fa-user text-blue-600"></i>
                        </span>
                        <input id="username" name="username" type="text" placeholder="Masukkan username Anda" required
                            class="w-full py-2 px-3 focus:outline-none placeholder-gray-400">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="flex items-center border border-gray-300 rounded-md overflow-hidden transition-all duration-200 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                        <span class="bg-gray-100 text-gray-500 px-3 py-2">
                            <i class="fas fa-lock text-blue-600"></i>
                        </span>
                        <input id="password" name="password" type="password" placeholder="Masukkan password Anda" required
                            class="w-full py-2 px-3 focus:outline-none placeholder-gray-400">
                    </div>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat username</label>
                </div>

                <div class="mb-4">
                    <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-md transition duration-200 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>