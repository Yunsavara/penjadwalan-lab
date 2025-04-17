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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->text('spesifikasi_barang');
            $table->text('deskripsi_barang');
            $table->unsignedBigInteger('lab_id');
            $table->enum('status',['rusak','tersedia','tidak dipakai'])->default('tidak dipakai');
            $table->foreign('lab_id')->references('id')->on('laboratorium_unpams');
            $table->unsignedBigInteger('meja_id')->nullable();
            $table->foreign('meja_id')->references('id')->on('barangs');
            $table->timestamps();
            // ->onDelete('set null')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
