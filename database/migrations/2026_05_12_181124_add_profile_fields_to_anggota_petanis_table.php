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
        Schema::table('anggota_petanis', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('luas_lahan');
            $table->integer('umur')->nullable()->after('jabatan');
            $table->string('tingkat_pendidikan')->nullable()->after('umur');
            $table->integer('jumlah_tanggungan')->nullable()->after('tingkat_pendidikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota_petanis', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'umur', 'tingkat_pendidikan', 'jumlah_tanggungan']);
        });
    }
};
