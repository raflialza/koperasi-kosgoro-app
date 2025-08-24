<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controller utama
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;

// Controller khusus Admin
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\SimpananController;
use App\Http\Controllers\Admin\PinjamanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ShuController;
use App\Http\Controllers\Admin\SemuaTransaksiController;

/*
|--------------------------------------------------------------------------
| Rute Halaman Publik & Autentikasi
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Halaman Utama (Home/Dashboard) Setelah Login
|--------------------------------------------------------------------------
*/
Route::get('/home', [DashboardController::class, 'index'])->name('home')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Khusus Role ANGGOTA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/profil', fn () => view('anggota.data-diri'))->name('dataDiri');
    Route::get('/simpanan', [TransaksiController::class, 'lihatSimpanan'])->name('simpanan.riwayat');
    Route::prefix('pinjaman')->name('pinjaman.')->group(function() {
        Route::get('/', [TransaksiController::class, 'lihatPinjaman'])->name('riwayat');
        Route::get('/ajukan', [TransaksiController::class, 'ajukanPinjamanForm'])->name('ajukan');
        Route::post('/ajukan', [TransaksiController::class, 'prosesAjukanPinjaman'])->name('proses_ajukan');
        Route::get('/{id}/detail', [TransaksiController::class, 'detailPinjamanAnggota'])->name('detail');
        Route::get('/{id}/invoice', [TransaksiController::class, 'cetakInvoicePinjaman'])->name('invoice');
    });
});

/*
|--------------------------------------------------------------------------
| Rute Khusus Role ADMIN & SUPER ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::resource('anggota', AnggotaController::class)->parameter('anggota', 'anggota');

    Route::get('simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
    Route::get('simpanan/create', [SimpananController::class, 'create'])->name('simpanan.create');
    Route::post('simpanan', [SimpananController::class, 'store'])->name('simpanan.store');
    Route::get('simpanan/{user}', [SimpananController::class, 'show'])->name('simpanan.show');

    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index');
        Route::get('/pengajuan', [PinjamanController::class, 'pengajuan'])->name('pengajuan');
        Route::get('/{id}', [PinjamanController::class, 'show'])->name('show');
        Route::put('/{id}/status', [PinjamanController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/angsuran', [PinjamanController::class, 'storeAngsuran'])->name('storeAngsuran');
        Route::post('/bayar-massal', [PinjamanController::class, 'storeAngsuranMassal'])->name('bayar-massal');
        Route::get('/{id}/invoice', [PinjamanController::class, 'cetakInvoice'])->name('invoice');
    });
    
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/', [LaporanController::class, 'index'])->name('index'); // Halaman filter
        Route::get('/simpanan/pdf', [LaporanController::class, 'simpananPdf'])->name('simpanan.pdf');
        Route::get('/pinjaman/pdf', [LaporanController::class, 'pinjamanPdf'])->name('pinjaman.pdf');
        Route::get('/simpanan/pdf/keseluruhan', [LaporanController::class, 'simpananPdfKeseluruhan'])->name('simpanan.pdf.keseluruhan');
        Route::get('/pinjaman/pdf/keseluruhan', [LaporanController::class, 'pinjamanPdfKeseluruhan'])->name('pinjaman.pdf.keseluruhan');
        Route::get('/shu', [ShuController::class, 'index'])->name('shu.index');
        Route::get('/shu/pdf', [ShuController::class, 'cetakPdf'])->name('shu.cetak');
    });

    // --- RUTE BARU UNTUK SEMUA TRANSAKSI ---
    Route::get('semua-transaksi', [SemuaTransaksiController::class, 'index'])->name('transaksi.semua');
});
