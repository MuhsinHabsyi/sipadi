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
        Schema::create('pengadaans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_barang', ['Bibit', 'Pupuk']);
            $table->enum('asal_barang', ['Beli', 'Dinas']);
            $table->integer('jumlah');
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->text('keadaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaans');
    }
};
