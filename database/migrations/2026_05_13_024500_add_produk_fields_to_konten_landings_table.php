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
        Schema::table('konten_landings', function (Blueprint $table) {
            $table->string('produk_nama')->nullable()->default('Beras Pandan Wangi');
            $table->text('produk_deskripsi')->nullable();
            $table->integer('produk_harga')->nullable()->default(15000);
            $table->integer('produk_stok_dijual')->nullable()->default(500);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konten_landings', function (Blueprint $table) {
            $table->dropColumn([
                'produk_nama',
                'produk_deskripsi',
                'produk_harga',
                'produk_stok_dijual',
            ]);
        });
    }
};
