<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controller utama
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;

// Controller khusus Admin (setelah direstrukturisasi)
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\SimpananController;
use App\Http\Controllers\Admin\PinjamanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SemuaTransaksiController;
/*
|--------------------------------------------------------------------------
| Rute Halaman Publik & Autentikasi
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

// Middleware 'guest' memastikan halaman ini hanya bisa diakses oleh user yang belum login.
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

// Middleware 'auth' memastikan rute ini hanya bisa diakses oleh user yang sudah login.
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Halaman Utama (Home/Dashboard) Setelah Login
|--------------------------------------------------------------------------
*/
// Rute ini akan mengarahkan user ke dashboard yang sesuai berdasarkan rolenya.
Route::get('/home', [DashboardController::class, 'index'])->name('home')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Khusus Role ANGGOTA
|--------------------------------------------------------------------------
| Semua rute di sini hanya bisa diakses oleh user dengan role 'anggota'.
*/
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    
    Route::get('/profil', fn () => view('anggota.data-diri'))->name('dataDiri');
    
    // Riwayat Simpanan Anggota (menggunakan TransaksiController yang sudah dirampingkan)
    Route::get('/simpanan', [TransaksiController::class, 'lihatSimpanan'])->name('simpanan.riwayat');

    // Grup untuk Pinjaman Anggota
    Route::prefix('pinjaman')->name('pinjaman.')->group(function() {
        Route::get('/', [TransaksiController::class, 'lihatPinjaman'])->name('riwayat');
        Route::get('/ajukan', [TransaksiController::class, 'ajukanPinjamanForm'])->name('ajukan');
        Route::post('/ajukan', [TransaksiController::class, 'prosesAjukanPinjaman'])->name('proses_ajukan');
        Route::get('/{id}/detail', [TransaksiController::class, 'detailPinjamanAnggota'])->name('detail');
    });
});

/*
|--------------------------------------------------------------------------
| Rute Khusus Role ADMIN & SUPER ADMIN
|--------------------------------------------------------------------------
| Semua rute di sini hanya bisa diakses oleh user dengan role 'admin' atau 'super_admin'.
*/
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Kelola Anggota (menggunakan Route::resource untuk CRUD yang lebih bersih)
    // Pastikan file AnggotaController.php dipindah ke folder Admin
    Route::resource('anggota', AnggotaController::class)->parameters([
    'anggota' => 'anggota'
]);

    // Kelola Simpanan (menggunakan Route::resource)
    // Ini akan otomatis membuat rute untuk index, create, store, show, edit, update, destroy.
    Route::get('simpanan/{user}', [SimpananController::class, 'show'])->name('simpanan.show');
    Route::resource('simpanan', SimpananController::class)->except(['show']);
    // RUTE BARU untuk Semua Transaksi
    Route::get('semua-transaksi', [SemuaTransaksiController::class, 'index'])->name('transaksi.semua');

    // Kelola Pinjaman (menggunakan controller & rute yang lebih terstruktur)
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index'); // Menggantikan .semua dan .pengajuan
        Route::get('/pengajuan', [PinjamanController::class, 'pengajuan'])->name('pengajuan'); // RUTE BARU untuk Proses Pengajuan
        Route::get('/{id}', [PinjamanController::class, 'show'])->name('show'); // Menggantikan .bayar
        Route::put('/{id}/status', [PinjamanController::class, 'updateStatus'])->name('updateStatus'); // Menggantikan .proses
        Route::post('/{id}/angsuran', [PinjamanController::class, 'storeAngsuran'])->name('storeAngsuran'); // Menggantikan .proses-bayar
        Route::post('/bayar-massal', [PinjamanController::class, 'storeAngsuranMassal'])->name('bayar-massal');
    });

    // Laporan PDF
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/simpanan/pdf', [LaporanController::class, 'simpananPdf'])->name('simpanan.pdf');
        Route::get('/pinjaman/pdf', [LaporanController::class, 'pinjamanPdf'])->name('pinjaman.pdf');
    });
});