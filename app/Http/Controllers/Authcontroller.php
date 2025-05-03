<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Pastikan untuk mengimpor model User

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah username ada di database
        $user = User::where('username', $credentials['username'])->first();

        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate(); 
            return redirect()->intended('home'); 
        }

        return back()->with('loginError', 'Login Gagal');
    }

    public function logout(Request $request){   
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // session()->flush();
        return redirect('/login')->with('success', 'Berhasil logout');
    }

}