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
        Schema::table('stok_beras', function (Blueprint $table) {
            $table->float('stok_ecommerce')->default(0);
            $table->float('stok_distributor')->default(0);
            $table->float('stok_gudang')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_beras', function (Blueprint $table) {
            $table->dropColumn(['stok_ecommerce', 'stok_distributor', 'stok_gudang']);
        });
    }
};
