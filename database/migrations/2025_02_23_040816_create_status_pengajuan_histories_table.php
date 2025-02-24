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
        Schema::create('status_pengajuan_histories', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'belum digunakan', 'sedang digunakan','dibatalkan','tergantikan']);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('lab_id');
            $table->foreign('lab_id')->references('id')->on('laboratorium_unpams');

            $table->unsignedBigInteger('changed_by');
            $table->foreign('changed_by')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pengajuan_histories');
    }
};
