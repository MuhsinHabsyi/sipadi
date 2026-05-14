<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontenLanding extends Model
{
    protected $table = 'konten_landings';

    protected $fillable = [
        'judul_hero',
        'deskripsi_hero',
        'judul_toko',
        'deskripsi_toko',
        'produk_nama',
        'produk_deskripsi',
        'produk_harga',
        'produk_stok_dijual',
        'produk_gambar',
    ];
}
