<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\SimpananController;
use App\Http\Controllers\Admin\PinjamanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ShuController;
use App\Http\Controllers\Admin\SemuaTransaksiController;

/*
|--------------------------------------------------------------------------
| Rute Publik & Autentikasi
|--------------------------------------------------------------------------
|
| Rute ini dapat diakses oleh semua pengunjung.
| Middleware 'guest' memastikan hanya user yang belum login bisa mengaksesnya.
|
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Rute Umum Setelah Login
|--------------------------------------------------------------------------
|
| Rute ini memerlukan autentikasi dan bisa diakses oleh semua role.
|
*/
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('home', [DashboardController::class, 'index'])->name('home');
});

/*
|--------------------------------------------------------------------------
| Rute Khusus Anggota
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('profil', [TransaksiController::class, 'dataDiri'])->name('profil');
    Route::get('simpanan', [TransaksiController::class, 'lihatSimpanan'])->name('simpanan');

    Route::prefix('pinjaman')->name('pinjaman.')->group(function() {
        Route::get('/', [TransaksiController::class, 'lihatPinjaman'])->name('index');
        Route::get('ajukan', [TransaksiController::class, 'ajukanPinjamanForm'])->name('ajukan');
        Route::post('ajukan', [TransaksiController::class, 'prosesAjukanPinjaman'])->name('store');
        Route::get('{pinjaman}/detail', [TransaksiController::class, 'detailPinjamanAnggota'])->name('detail');
        Route::get('{pinjaman}/invoice', [TransaksiController::class, 'cetakInvoicePinjaman'])->name('invoice');
    });
});

/*
|--------------------------------------------------------------------------
| Rute Khusus Admin & Super Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Manajemen Anggota
    Route::resource('anggota', AnggotaController::class);

    // Manajemen Simpanan
    Route::get('simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
    Route::get('simpanan/tambah/{user}', [SimpananController::class, 'create'])->name('simpanan.create');
    Route::post('simpanan', [SimpananController::class, 'store'])->name('simpanan.store');
    Route::get('simpanan/{user}', [SimpananController::class, 'show'])->name('simpanan.show');

    // Manajemen Pinjaman
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index');
        Route::get('pengajuan', [PinjamanController::class, 'pengajuan'])->name('pengajuan');
        Route::post('{pinjaman}/approve', [PinjamanController::class, 'approve'])->name('approve');
        Route::post('{pinjaman}/reject', [PinjamanController::class, 'reject'])->name('reject');
        Route::get('{pinjaman}', [PinjamanController::class, 'show'])->name('show');
        Route::post('{pinjaman}/angsuran', [PinjamanController::class, 'storeAngsuran'])->name('angsuran.store');
        Route::get('{pinjaman}/invoice', [PinjamanController::class, 'cetakInvoice'])->name('invoice');
    });
    
    // Laporan & SHU
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::post('simpanan/pdf', [LaporanController::class, 'simpananPdf'])->name('simpanan.pdf');
        Route::post('pinjaman/pdf', [LaporanController::class, 'pinjamanPdf'])->name('pinjaman.pdf');
        
        Route::get('shu', [ShuController::class, 'index'])->name('shu.index');
        Route::post('shu/pdf', [ShuController::class, 'cetakPdf'])->name('shu.pdf');
    });

    // Semua Transaksi
    Route::get('semua-transaksi', [SemuaTransaksiController::class, 'index'])->name('transaksi.semua');
});
