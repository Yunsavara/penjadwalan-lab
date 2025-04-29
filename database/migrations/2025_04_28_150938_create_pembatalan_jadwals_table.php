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
        Schema::create('pembatalan_jadwals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jadwal_booking_id')->constrained('jadwal_bookings');
            $table->text('alasan_pembatalan_jadwal');
            $table->text('balasan_pembatalan_jadwal')->nullable();
            $table->enum('status_pembatalan_jadwal', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');

            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembatalan_jadwals');
    }
};
