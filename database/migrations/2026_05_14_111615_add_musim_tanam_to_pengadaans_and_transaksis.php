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
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->string('musim_tanam')->nullable()->after('jenis_barang');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('musim_tanam')->nullable()->after('jenis_beras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengadaans', function (Blueprint $table) {
            $table->dropColumn('musim_tanam');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('musim_tanam');
        });
    }
};
