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
            $table->decimal('harga_per_kilo', 12, 2)->default(0)->after('stok_gudang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_beras', function (Blueprint $table) {
            $table->dropColumn('harga_per_kilo');
        });
    }
};
