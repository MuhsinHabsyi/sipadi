<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnggotaPetani extends Model
{
    protected $table = 'anggota_petanis';

    protected $fillable = [
        'nama_petani',
        'luas_lahan',
        'jabatan',
        'umur',
        'tingkat_pendidikan',
        'jumlah_tanggungan',
        'no_hp',
        'alamat',
        'nama_lahan',
        'alamat_lahan',
        'status_lahan',
    ];

    // Pilihan dropdown Status Lahan
    const STATUS_LAHAN_OPTIONS = [
        'Lahan Milik' => 'Lahan Milik',
        'Lahan Sewa'  => 'Lahan Sewa',
    ];

    // Pilihan dropdown Jabatan
    const JABATAN_OPTIONS = [
        'Ketua'      => 'Ketua',
        'Sekretaris' => 'Sekretaris',
        'Bendahara'  => 'Bendahara',
        'Anggota'    => 'Anggota',
    ];

    // Pilihan dropdown Tingkat Pendidikan
    const PENDIDIKAN_OPTIONS = [
        'SD'      => 'SD',
        'SMP'     => 'SMP',
        'SMA/SMK' => 'SMA/SMK',
        'D3'      => 'D3',
        'S1'      => 'S1',
        'S2'      => 'S2',
        'Lainnya' => 'Lainnya',
    ];

    public function alokasiBibits(): HasMany
    {
        return $this->hasMany(AlokasiBibit::class, 'id_petani');
    }

    public function dataPanens(): HasMany
    {
        return $this->hasMany(DataPanen::class, 'id_petani');
    }

    public function pembagianHasils(): HasMany
    {
        return $this->hasMany(PembagianHasil::class, 'id_petani');
    }
}
