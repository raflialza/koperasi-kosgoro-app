<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            // Menambahkan kolom setelah 'jumlah_pinjaman'
            $table->decimal('total_tagihan', 15, 2)->after('jumlah_pinjaman')->default(0);
            $table->float('persentase_bunga')->after('jumlah_pinjaman')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropColumn(['total_tagihan', 'persentase_bunga']);
        });
    }
};