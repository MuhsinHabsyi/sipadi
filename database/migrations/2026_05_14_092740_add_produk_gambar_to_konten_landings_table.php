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
            $table->string('produk_gambar')->nullable()->after('produk_stok_dijual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konten_landings', function (Blueprint $table) {
            $table->dropColumn('produk_gambar');
        });
    }
};
