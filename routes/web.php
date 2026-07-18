<?php

use App\Http\Controllers\CheckinController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HistoryController as AdminHistoryController;
use App\Http\Controllers\Admin\ImportJadwalController as AdminImportJadwalController;
use App\Http\Controllers\Admin\JadwalPraktikumController as AdminJadwalPraktikumController;
use App\Http\Controllers\Admin\LaboratoriumController as AdminLaboratoriumController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\RekapController as AdminRekapController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\TahunAjaranController as AdminTahunAjaranController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Aslab\DashboardController as AslabDashboardController;
use App\Http\Controllers\Aslab\ReservasiController as AslabReservasiController;
use App\Http\Controllers\Aslab\VerifikasiController as AslabVerifikasiController;
use App\Http\Controllers\Aslab\HistoryController as AslabHistoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
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

// Profil (semua role yang login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Reservasi peminjam (perlu login)
Route::middleware('auth')->group(function () {
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/buat', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
    // Checkin harus sebelum route {id} agar tidak tertimpa
    Route::get('/reservasi/checkin/{kode}', [CheckinController::class, 'scan'])->name('reservasi.checkin');
    Route::get('/reservasi/{id}/edit', [ReservasiController::class, 'edit'])->name('reservasi.edit');
    Route::put('/reservasi/{id}', [ReservasiController::class, 'update'])->name('reservasi.update');
    Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
    Route::get('/reservasi/{id}', [ReservasiController::class, 'show'])->name('reservasi.show');
});

// Area admin (laboran)
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('laboratorium', AdminLaboratoriumController::class)->except(['show']);

    // Jadwal praktikum — laboran isi jadwal & lab yang dipakai per hari
    Route::get('/jadwal-praktikum', [AdminJadwalPraktikumController::class, 'index'])->name('jadwal-praktikum.index');
    Route::get('/jadwal-praktikum/buat', [AdminJadwalPraktikumController::class, 'create'])->name('jadwal-praktikum.create');
    Route::post('/jadwal-praktikum', [AdminJadwalPraktikumController::class, 'store'])->name('jadwal-praktikum.store');
    Route::get('/jadwal-praktikum/{id}/edit', [AdminJadwalPraktikumController::class, 'edit'])->name('jadwal-praktikum.edit');
    Route::put('/jadwal-praktikum/{id}', [AdminJadwalPraktikumController::class, 'update'])->name('jadwal-praktikum.update');
    Route::delete('/jadwal-praktikum/{id}', [AdminJadwalPraktikumController::class, 'destroy'])->name('jadwal-praktikum.destroy');

    // Import jadwal kuliah dari Excel/CSV
    Route::get('/jadwal/import', [AdminImportJadwalController::class, 'index'])->name('jadwal.import');
    Route::get('/jadwal/import/template', [AdminImportJadwalController::class, 'template'])->name('jadwal.template');
    Route::post('/jadwal/import', [AdminImportJadwalController::class, 'import'])->name('jadwal.doImport');

    // Reservasi laboran — CRUD lengkap
    Route::get('/reservasi', [AdminReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/buat', [AdminReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [AdminReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/{id}/edit', [AdminReservasiController::class, 'edit'])->name('reservasi.edit');
    Route::put('/reservasi/{id}', [AdminReservasiController::class, 'update'])->name('reservasi.update');
    Route::delete('/reservasi/{id}', [AdminReservasiController::class, 'destroy'])->name('reservasi.destroy');
    Route::get('/reservasi/{id}', [AdminReservasiController::class, 'show'])->name('reservasi.show');
    Route::patch('/reservasi/{id}/status', [AdminReservasiController::class, 'updateStatus'])->name('reservasi.updateStatus');

    // History laboran — dengan export
    Route::get('/history', [AdminHistoryController::class, 'index'])->name('history.index');
    Route::get('/history/export', [AdminHistoryController::class, 'export'])->name('history.export');
    Route::post('/history/{id}/restore', [AdminHistoryController::class, 'restore'])->name('history.restore');
    Route::delete('/history/{id}/force-delete', [AdminHistoryController::class, 'forceDelete'])->name('history.forceDelete');

    // Manajemen pengguna (termasuk buat akun aslab)
    Route::resource('user', AdminUserController::class)->except(['show']);

    // Tahun ajaran (master data untuk filter Rekap)
    Route::resource('tahun-ajaran', AdminTahunAjaranController::class)->except(['show']);

    // Mata kuliah (master data untuk filter Rekap & form reservasi)
    Route::resource('mata-kuliah', AdminMataKuliahController::class)->except(['show']);

    // Rekap penggunaan laboratorium
    Route::get('/rekap', [AdminRekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export-pdf', [AdminRekapController::class, 'exportPdf'])->name('rekap.exportPdf');
    Route::get('/rekap/export-excel', [AdminRekapController::class, 'exportExcel'])->name('rekap.exportExcel');
});

// Area aslab
Route::prefix('aslab')->name('aslab.')->middleware(['auth', 'aslab'])->group(function () {
    Route::get('/', [AslabDashboardController::class, 'index'])->name('dashboard');

    // Reservasi aslab — CRUD lengkap
    Route::get('/reservasi', [AslabReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/buat', [AslabReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [AslabReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/{id}/edit', [AslabReservasiController::class, 'edit'])->name('reservasi.edit');
    Route::put('/reservasi/{id}', [AslabReservasiController::class, 'update'])->name('reservasi.update');
    Route::delete('/reservasi/{id}', [AslabReservasiController::class, 'destroy'])->name('reservasi.destroy');
    Route::get('/reservasi/{id}', [AslabReservasiController::class, 'show'])->name('reservasi.show');

    Route::get('/verifikasi', [AslabVerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('/verifikasi/{id}', [AslabVerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::post('/verifikasi/{id}/setujui', [AslabVerifikasiController::class, 'setujui'])->name('verifikasi.setujui');
    Route::post('/verifikasi/{id}/tolak', [AslabVerifikasiController::class, 'tolak'])->name('verifikasi.tolak');

    Route::get('/history', [AslabHistoryController::class, 'index'])->name('history.index');
});
