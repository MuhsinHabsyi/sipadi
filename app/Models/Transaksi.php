<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'id_pelanggan',
        'jenis_beras',
        'harga_satuan',
        'tanggal_pesanan',
        'jumlah_pesanan',
        'total_harga',
        'bukti_transfer',
        'status_pesanan',
        'tipe_transaksi',
        'nama_pelanggan_manual',
        'alamat_pengantaran',
        'musim_tanam',
    ];

    protected $casts = [
        'tanggal_pesanan' => 'date',
    ];

    // Status constants
    const STATUS_MENUNGGU   = 'Menunggu';
    const STATUS_SELESAI    = 'Selesai';
    const STATUS_DIBATALKAN = 'Dibatalkan';

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}
