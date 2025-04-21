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
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('booking_detail_id');
            $table->foreign('booking_detail_id')->references('id')->on('booking_details');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('status', ['pending', 'diterima', 'digunakan', 'selesai', 'ditolak', 'dibatalkan', 'digantikan']); // Pengajuan pembatalan tambah status pengajuan pembatalan
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_logs');
    }
};
