<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StokBeras extends Model
{
    protected $table = 'stok_beras';

    protected $fillable = [
        'ketersediaan_stok',
        'stok_ecommerce',
        'stok_distributor',
        'stok_gudang',
        'harga_per_kilo',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_stok');
    }
}
