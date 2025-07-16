<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_simpanan', ['pokok', 'wajib']);
            $table->year('tahun');
            $table->integer('bulan'); // 1-12
            $table->decimal('saldo_awal', 15, 2)->default(0); // Saldo awal tahun
            $table->decimal('jumlah', 15, 2); // Jumlah transaksi
            $table->decimal('saldo_akhir', 15, 2); // Saldo setelah transaksi
            $table->date('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};