<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InputPelanggaranController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SanksiController;
use App\Http\Controllers\SiswaController;
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
Route::resource('input-pelanggaran', InputPelanggaranController::class)-> middleware('auth');

// untuk input pelanggaran
Route::get('input-pelanggaran', [InputPelanggaranController::class, 'index'])->name('input-pelanggaran.index');
Route::get('input-pelanggaran/create', [InputPelanggaranController::class, 'create'])->name('input-pelanggaran.create');
Route::post('input-pelanggaran', [InputPelanggaranController::class, 'store'])->name('input-pelanggaran.store');

// import excel list siswa 
Route::get('/siswa-import', [SiswaController::class, 'import'])->middleware('auth');
Route::post('/siswa-import', [SiswaController::class, 'import'])->name('siswa.import')->middleware('auth');
Route::resource('sanksi', SanksiController::class)-> middleware('auth');
Route::resource('siswa', SiswaController::class)-> middleware('auth');
Route::resource('kelas', KelasController::class)-> middleware('auth')->parameters(['kelas' => 'kelas']);
Route::get('/siswa-import', [SiswaController::class, 'import'])->middleware('auth');
Route::post('/siswa-import', [SiswaController::class, 'import'])->name('siswa.import')->middleware('auth');
// Route::get('/show-hash', [AuthController::class, 'showPasswordHash']);
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');


