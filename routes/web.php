<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\SimpananController;


// HALAMAN UTAMA (Login)
Route::get('/', fn () => view('auth/login'));
// LOGIN & LOGOUT
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// DASHBOARD UTAMA (SETELAH LOGIN)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    // Data diri anggota
    Route::get('/data-diri', fn () => view('anggota.data-diri'))->name('anggota.dataDiri');
    // Simpanan saya (khusus anggota)
    Route::middleware('role:anggota')->get('/simpanan-saya', [SimpananController::class, 'simpananSaya'])->name('anggota.simpanan');
});

// ROUTE ADMIN & SUPER ADMIN
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Kelola anggota
    Route::resource('anggota', AnggotaController::class);
    // Halaman simpanan (khusus admin/super admin)
    Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
});
