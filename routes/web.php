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
// ShuController tidak lagi digunakan
use App\Http\Controllers\Admin\SemuaTransaksiController;
use App\Http\Controllers\Admin\PengaturanController;

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
    
    Route::resource('anggota', AnggotaController::class)->parameters([
        'anggota' => 'anggota'
    ]);
    
    Route::prefix('simpanan')->name('simpanan.')->group(function () {
        Route::get('/', [SimpananController::class, 'index'])->name('index');
        Route::get('create/{user?}', [SimpananController::class, 'create'])->name('create');
        Route::post('/', [SimpananController::class, 'store'])->name('store');
        Route::get('{user}', [SimpananController::class, 'show'])->name('show');
    });

    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index');
        Route::get('/{pinjaman}', [PinjamanController::class, 'show'])->name('show'); // Disarankan diubah ke {pinjaman}
        Route::put('/{pinjaman}/status', [PinjamanController::class, 'updateStatus'])->name('updateStatus'); // Disarankan diubah ke {pinjaman}
        Route::post('/{pinjaman}/angsuran', [PinjamanController::class, 'storeAngsuran'])->name('storeAngsuran'); // Disarankan diubah ke {pinjaman}
        Route::post('/bayar-massal', [PinjamanController::class, 'storeAngsuranMassal'])->name('bayar-massal');
        
        // --- PERBAIKAN DI SINI ---
        Route::get('/{pinjaman}/invoice', [PinjamanController::class, 'cetakInvoice'])->name('invoice');
    });
    
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/simpanan/pdf', [LaporanController::class, 'simpananPdf'])->name('simpanan.pdf');
        Route::get('/pinjaman/pdf', [LaporanController::class, 'pinjamanPdf'])->name('pinjaman.pdf');
        Route::get('/simpanan/excel', [LaporanController::class, 'simpananExcel'])->name('simpanan.excel');
        Route::get('/pinjaman/excel', [LaporanController::class, 'pinjamanExcel'])->name('pinjaman.excel');
        Route::get('/shu', [LaporanController::class, 'shu'])->name('shu.index');
        Route::get('/shu/pdf', [LaporanController::class, 'shuPdf'])->name('shu.cetak');
    });

    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::post('/', [PengaturanController::class, 'update'])->name('update');
    });
    
    Route::get('semua-transaksi', [SemuaTransaksiController::class, 'index'])->name('transaksi.semua');
});

