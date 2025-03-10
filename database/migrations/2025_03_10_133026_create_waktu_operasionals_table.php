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
        Schema::create('waktu_operasionals', function (Blueprint $table) {
            $table->id();
            $table->json('hari_operasional');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedBigInteger('lokasi_id');
            $table->foreign('lokasi_id')
              ->references('id')
              ->on('lokasis');
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waktu_operasionals');
    }
};
