<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    protected $table = 'pengadaans';

    protected $fillable = [
        'jenis_barang',
        'musim_tanam',
        'asal_barang',
        'jumlah',
        'harga',
        'total_biaya',
        'keadaan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga' => 'float',
        'total_biaya' => 'float',
    ];
}
