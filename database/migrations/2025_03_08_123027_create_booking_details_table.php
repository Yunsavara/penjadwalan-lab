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
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();

            $table->string('kode_pengajuan');
            $table->foreign('kode_pengajuan')->references('kode_pengajuan')->on('bookings');

            $table->unsignedBigInteger('lab_id');
            $table->foreign('lab_id')->references('id')->on('laboratorium_unpams');

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['pending', 'diterima', 'digunakan', 'selesai', 'ditolak', 'dibatalkan', 'digantikan'])->default('pending'); // langsung kebooking
            $table->text('keperluan');
            $table->timestamps();

            // buat table baru => Pengajuan_Booking, Booking, Booking_log, Pengajuan_Log

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');
    }
};
