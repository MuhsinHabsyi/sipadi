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
        Schema::create('konten_landings', function (Blueprint $table) {
            $table->id();
            $table->string('judul_hero');
            $table->text('deskripsi_hero');
            $table->string('judul_toko');
            $table->text('deskripsi_toko');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten_landings');
    }
};
