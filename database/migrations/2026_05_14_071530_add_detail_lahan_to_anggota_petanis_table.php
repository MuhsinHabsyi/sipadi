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
            $table->string('no_hp')->nullable()->after('umur');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->string('nama_lahan')->nullable()->after('alamat');
            $table->text('alamat_lahan')->nullable()->after('nama_lahan');
            $table->string('status_lahan')->nullable()->after('alamat_lahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota_petanis', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'alamat', 'nama_lahan', 'alamat_lahan', 'status_lahan']);
        });
    }
};
