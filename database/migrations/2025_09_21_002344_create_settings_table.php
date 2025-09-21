<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->timestamps();
        });

        // Menambahkan nilai default saat tabel dibuat
        DB::table('settings')->insert([
            [
                'key' => 'simpanan_pokok_otomatis',
                'value' => '100000',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'simpanan_wajib_otomatis',
                'value' => '50000',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
