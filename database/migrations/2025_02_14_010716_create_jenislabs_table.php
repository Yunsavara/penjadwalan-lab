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
        Schema::create('jenislabs', function (Blueprint $table) {
            $table->id();
            $table->string("name_jenis_lab")->unique();
            $table->string("slug_jenis_lab")->unique();
            $table->text("description_jenis_lab")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenislabs');
    }
};
