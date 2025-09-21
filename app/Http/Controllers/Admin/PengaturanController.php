<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class PengaturanController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index()
    {
        // Ambil data dari database, jika tidak ada, gunakan nilai default
        $simpananPokok = Setting::where('key', 'simpanan_pokok_otomatis')->firstOrCreate(
            ['key' => 'simpanan_pokok_otomatis'],
            ['value' => '100000']
        );
        $simpananWajib = Setting::where('key', 'simpanan_wajib_otomatis')->firstOrCreate(
            ['key' => 'simpanan_wajib_otomatis'],
            ['value' => '50000']
        );

        return view('admin.pengaturan.index', compact('simpananPokok', 'simpananWajib'));
    }

    /**
     * Menyimpan perubahan pengaturan.
     */
    public function update(Request $request)
    {
        $request->validate([
            'simpanan_pokok_otomatis' => 'required|numeric|min:0',
            'simpanan_wajib_otomatis' => 'required|numeric|min:0',
        ]);

        Setting::updateOrCreate(
            ['key' => 'simpanan_pokok_otomatis'],
            ['value' => $request->simpanan_pokok_otomatis]
        );

        Setting::updateOrCreate(
            ['key' => 'simpanan_wajib_otomatis'],
            ['value' => $request->simpanan_wajib_otomatis]
        );

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}

