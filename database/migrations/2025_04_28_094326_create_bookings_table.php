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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking');
            $table->date('tanggal_booking');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('alasan_booking');
            $table->text('balasan_booking');
            $table->enum('status',['menunggu','diterima','ditolak','dibatalkan','dipindahkan','menunggu dibatalkan']);

            // Relasi
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('laboratorium_unpam_id')->constrained('laboratorium_unpams');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
