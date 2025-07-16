<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->year('tahun');
            $table->decimal('saldo_pinjaman_awal', 15, 2)->default(0); // Saldo pinjaman awal tahun
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->decimal('bunga', 5, 2);
            $table->integer('jangka_waktu');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');
            $table->text('keperluan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};