<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="bg-cover bg-center bg-fixed min-h-screen" style="background-image: url('{{ asset('images/bgsmk.jpeg') }}')">
        <div class="h-full  bg-opacity-70 py-20 p-4 md:p-20 lg:p-32">
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

                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="username" class="block text-blue-700 font-bold mb-2">Username</label>
                            <input id="username" name="username" type="text" placeholder="Username" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-blue-700 font-bold mb-2">Password</label>
                            <input id="password" name="password" type="password" placeholder="Password" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex items-center justify-center">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
