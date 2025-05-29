<?php

use App\Http\Controllers\JenisController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SanksiController;
use App\Http\Controllers\SiswaController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;


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
Route::resource('sanksi', SanksiController::class)-> middleware('auth');
Route::resource('siswa', SiswaController::class)-> middleware('auth');
Route::resource('kelas', KelasController::class)-> middleware('auth')->parameters(['kelas' => 'kelas']);
Route::get('/siswa-import', [SiswaController::class, 'import'])->middleware('auth');
Route::post('/siswa-import', [SiswaController::class, 'import'])->name('siswa.import')->middleware('auth');
// Route::get('/show-hash', [AuthController::class, 'showPasswordHash']);
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');


