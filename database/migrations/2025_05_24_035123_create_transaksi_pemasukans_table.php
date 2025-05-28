<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_pemasukans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('master_pemasukan_id')->constrained('master_pemasukans')->onDelete('cascade'); // Relasi ke tabel master_pemasukans
            $table->decimal('jumlah', 15, 2); // Jumlah uang, 15 digit total, 2 di belakang koma
            $table->date('tanggal_transaksi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pemasukans');
    }
};
