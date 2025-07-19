<?php

use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InputPelanggaranController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SanksiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Redirect root to home
Route::get('/', function () {
    return redirect('/home');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
    // Jika ingin menambahkan registrasi
    // Route::get('/register', [AuthController::class, 'register'])->name('register');
    // Route::post('/register', [AuthController::class, 'storeRegistration'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes (for all logged in users)
Route::middleware('auth')->group(function () {
    // Home Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Dashboard Routes
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    // Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    // Route::get('/user/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');

    // Siswa Routes
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa-import', [SiswaController::class, 'import'])->name('siswa.import.view');
    Route::post('/siswa-import', [SiswaController::class, 'import'])->name('siswa.import');

    // Pelanggaran Routes
    Route::resource('input_pelanggaran', InputPelanggaranController::class);
    Route::patch('/input_pelanggaran/{pelanggaran}/status', [InputPelanggaranController::class, 'updateStatus'])
         ->name('input_pelanggaran.update-status');

    // Laporan Routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');

    // Routes untuk User
    Route::prefix('user')->group(function () {
        Route::get('/pelanggaran-siswa', [InputPelanggaranController::class, 'pelanggaranSiswa'])
            ->name('user.pelanggaran_siswa');
        Route::get('/data-kelas', [KelasController::class, 'index'])
            ->name('user.data_kelas');
        Route::get('/data-siswa', [SiswaController::class, 'index'])
            ->name('user.data_siswa');
        });
});

// Admin-only Routes
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    // Kategori Routes
    Route::resource('kategori', KategoriController::class);

    // Jenis Routes
    Route::resource('jenis', JenisController::class)->parameters(['jenis' => 'jenis']);

    // Sanksi Routes
    Route::resource('sanksi', SanksiController::class);

    // Kelas Routes
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);

    // Tahun Ajaran Routes
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::patch('/tahun-ajaran/{id}/aktifkan', [TahunAjaranController::class, 'aktifkan'])
         ->name('tahun-ajaran.aktifkan');

});

// Fallback Route
Route::fallback(function () {
    return view('errors.404');
});
