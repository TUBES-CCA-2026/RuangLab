<?php

use App\Http\Controllers\CheckinController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaboratoriumController as AdminLaboratoriumController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Aslab\DashboardController as AslabDashboardController;
use App\Http\Controllers\Aslab\VerifikasiController as AslabVerifikasiController;
use App\Http\Controllers\Aslab\HistoryController as AslabHistoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\ReservasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/laboratorium', [LaboratoriumController::class, 'index'])->name('laboratorium.index');
Route::get('/laboratorium/{id}', [LaboratoriumController::class, 'show'])->name('laboratorium.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Reservasi (perlu login)
Route::middleware('auth')->group(function () {
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/buat', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/{id}', [ReservasiController::class, 'show'])->name('reservasi.show');
    Route::get('/reservasi/checkin/{kode}', [CheckinController::class, 'scan'])
    ->name('reservasi.checkin');
});

// Area admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('laboratorium', AdminLaboratoriumController::class)->except(['show']);

    Route::get('/reservasi', [AdminReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/{id}', [AdminReservasiController::class, 'show'])->name('reservasi.show');
    Route::patch('/reservasi/{id}/status', [AdminReservasiController::class, 'updateStatus'])->name('reservasi.updateStatus');
});

Route::prefix('aslab')->name('aslab.')->middleware(['auth', 'aslab'])->group(function () {
    Route::get('/', [AslabDashboardController::class, 'index'])->name('dashboard');

    Route::get('/verifikasi', [AslabVerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/{id}', [AslabVerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::post('/verifikasi/{id}/setujui', [AslabVerifikasiController::class, 'setujui'])->name('verifikasi.setujui');
    Route::post('/verifikasi/{id}/tolak', [AslabVerifikasiController::class, 'tolak'])->name('verifikasi.tolak');

    Route::get('/history', [AslabHistoryController::class, 'index'])->name('history.index');
});
