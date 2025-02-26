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
            $table->string('name');
            $table->text('spesifikasi');
            $table->text('deskripsi');
            $table->unsignedBigInteger('lab_id');
            $table->foreign('lab_id')->on('id')->references('laboratorium_unpams');
            $table->unsignedBigInteger('meja_id')->nullable()->constrained('barangs');
            $table->timestamps();
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
