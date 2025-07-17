<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\SimpananController;

// Halaman utama
Route::get('/', function () {
    return view('auth/login');
});

// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

    Route::get('/data-diri', function () {
        return view('anggota.data-diri');
    })->name('anggota.dataDiri');
});
Route::middleware(['auth', 'role:anggota'])->get('/simpanan-saya', [SimpananController::class, 'simpananSaya'])->name('anggota.simpanan');

// Admin routes
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->group(function () {
    Route::resource('anggota', AnggotaController::class);
});
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->group(function () {
    Route::get('/simpanan', [SimpananController::class, 'index'])->name('admin.simpanan.index');
});
