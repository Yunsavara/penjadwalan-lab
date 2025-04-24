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
        Schema::create('pengajuan_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique();
            $table->enum('status_pengajuan_booking', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_pengajuan_booking')->nullable();

            $table->foreignId('user_id')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_bookings');
    }
};
