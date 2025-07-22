<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;


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
Route::get('/home', function () {
    $user = auth::user();
    if ($user->role === 'admin' || $user->role === 'super_admin') {
        return (new DashboardController)->index();
    } elseif ($user->role === 'anggota') {
        return redirect()->route('anggota.dataDiri');
    }
    return redirect('/');
})->name('home')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Khusus Role ANGGOTA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/profil', fn () => view('anggota.data-diri'))->name('dataDiri');
    
    // Riwayat Simpanan Anggota
    Route::get('/simpanan', [TransaksiController::class, 'lihatSimpanan'])->name('simpanan.riwayat');

    // Pengajuan & Riwayat Pinjaman Anggota
    Route::get('/pinjaman', [TransaksiController::class, 'lihatPinjaman'])->name('pinjaman.riwayat');
    Route::get('/pinjaman/ajukan', [TransaksiController::class, 'ajukanPinjamanForm'])->name('pinjaman.ajukan');
    Route::post('/pinjaman/ajukan', [TransaksiController::class, 'prosesAjukanPinjaman'])->name('pinjaman.proses_ajukan');
    Route::get('/pinjaman/{id}/detail', [TransaksiController::class, 'detailPinjamanAnggota'])->name('pinjaman.detail');
});

/*
|--------------------------------------------------------------------------
| Rute Khusus Role ADMIN & SUPER ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Kelola Anggota
    Route::resource('anggota', AnggotaController::class)->parameters([
    'anggota' => 'anggota',
]);
    // Kelola Simpanan
    Route::get('/simpanan', [TransaksiController::class, 'daftarSemuaSimpanan'])->name('simpanan.index');
    Route::get('/simpanan/tambah', [TransaksiController::class, 'tambahSimpananForm'])->name('simpanan.tambah');
    Route::post('/simpanan/tambah', [TransaksiController::class, 'prosesTambahSimpanan'])->name('simpanan.proses-tambah');
    
    // Kelola Pinjaman
    Route::get('/pinjaman/pengajuan', [TransaksiController::class, 'daftarPengajuanPinjaman'])->name('pinjaman.pengajuan');
    Route::post('/pinjaman/pengajuan/{id}/proses', [TransaksiController::class, 'prosesPengajuan'])->name('pinjaman.proses');
    Route::get('/pinjaman/semua', [TransaksiController::class, 'daftarSemuaPinjamanAdmin'])->name('pinjaman.semua');
    
    // Pembayaran Angsuran
    Route::get('/pinjaman/{id}/bayar', [TransaksiController::class, 'pembayaranAngsuranForm'])->name('pinjaman.bayar');
    Route::post('/pinjaman/{id}/bayar', [TransaksiController::class, 'prosesPembayaranAngsuran'])->name('pinjaman.proses-bayar');

    // Laporan PDF
    Route::get('/laporan/simpanan', [TransaksiController::class, 'cetakLaporanSimpanan'])->name('laporan.simpanan');
    Route::get('/laporan/pinjaman', [TransaksiController::class, 'cetakLaporanPinjaman'])->name('laporan.pinjaman');
});