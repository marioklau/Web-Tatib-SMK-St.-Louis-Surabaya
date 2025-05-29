<?php

use App\Http\Controllers\JenisController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;


Route::get('/', function () {
    return redirect('/home');
}) ->middleware('auth');


Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::resource('kategori', KategoriController::class)-> middleware('auth');
Route::resource('jenis', JenisController::class)-> middleware('auth') ->parameters(['jenis' => 'jenis']);;
// Route::get('/show-hash', [AuthController::class, 'showPasswordHash']);

