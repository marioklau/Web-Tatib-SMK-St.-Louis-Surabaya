<!DOCTYPE html>
<html lang="en">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
<div class="h-screen bg-gray-200 py-20 p-4 md:p-20 lg:p-32">
    <div class="max-w-sm bg-white rounded-lg overflow-hidden shadow-lg mx-auto">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-blue-800 mb-2 text-center">Selamat Datang</h2>
            <p class="text-blue-700 mb-6 text-center">Silahkan Masuk Ke Akun Anda!</p>

            {{-- Tampilkan pesan sukses --}}
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tampilkan pesan error --}}
            @if (session()->has('loginError'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('loginError') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-blue-700 font-bold mb-2" for="username">
                        Username
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="username" name="username" type="text" placeholder="Username" required>
                </div>
                <div class="mb-6">
                    <label class="block text-blue-700 font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="password" name="password" type="password" placeholder="Password" required>
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
