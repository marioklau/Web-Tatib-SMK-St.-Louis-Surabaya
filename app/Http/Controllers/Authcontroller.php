<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        return view('auth.login');
    }

    // Proses autentikasi login
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cari user berdasarkan username
        $user = User::where('username', $credentials['username'])->first();

        // Cek apakah user ditemukan dan password cocok
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate(); 
            return redirect()->route('dashboard'); 
        }

        // Jika gagal login
        return back()->with('loginError', 'Login Gagal');
    }

    // Logout user
    public function logout(Request $request)
    {   
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout');
    }

    // Optional: menampilkan hash dari password (untuk testing)
    // public function showPasswordHash()
    // {
    //     $password = 'admin'; // Ganti sesuai password yang ingin di-hash
    //     $hash = Hash::make($password);
    //     dd($hash); // tampilkan hash di browser
    // }
    
}
