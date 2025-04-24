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
        Schema::create('jadwal_bookings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_jadwal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status_jadwal_booking', ['menunggu','diterima','ditolak','dibatalkan'])->default('menunggu');

            $table->foreignId('pengajuan_booking_id')->constrained('pengajuan_bookings');
            $table->foreignId('laboratorium_unpam_id')->constrained('laboratorium_unpams');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_bookings');
    }
};
