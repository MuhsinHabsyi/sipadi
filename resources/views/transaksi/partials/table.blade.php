<div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 11px; color: var(--color-muted); text-transform: uppercase;">ID</th>
                <th style="padding: 12px; text-align: left; font-size: 11px; color: var(--color-muted); text-transform: uppercase;">Pelanggan</th>
                <th style="padding: 12px; text-align: left; font-size: 11px; color: var(--color-muted); text-transform: uppercase;">Jumlah</th>
                <th style="padding: 12px; text-align: right; font-size: 11px; color: var(--color-muted); text-transform: uppercase;">Total</th>
                <th style="padding: 12px; text-align: center; font-size: 11px; color: var(--color-muted); text-transform: uppercase;">Status</th>
                <th style="padding: 12px; text-align: right; font-size: 11px; color: var(--color-muted); text-transform: uppercase;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $t)
            <tr style="border-bottom: 1px solid var(--color-border); transition: background .2s;" onmouseover="this.style.background='rgba(0,0,0,0.01)'" onmouseout="this.style.background='transparent'">
                <td style="padding: 12px; font-size: 13px; color: var(--color-muted);">#{{ $t->id }}</td>
                <td style="padding: 12px;">
                    <div style="font-size: 13.5px; font-weight: 600; color: var(--color-text);">
                        {{ $t->pelanggan->nama ?? ($t->nama_pelanggan_manual ?? 'Umum') }}
                    </div>
                    <div style="font-size: 10px; color: var(--color-muted);">{{ $t->tanggal_pesanan->format('d M Y') }}</div>
                </td>
                <td style="padding: 12px;">
                    <div style="font-size: 13px; font-weight: 700; color: var(--color-text);">{{ number_format($t->jumlah_pesanan, 1) }} kg</div>
                </td>
                <td style="padding: 12px; text-align: right; font-size: 13px; font-weight: 700; color: var(--color-primary);">Rp {{ number_format($t->total_harga ?? 0, 0, ',', '.') }}</td>
                <td style="padding: 12px; text-align: center;">
                    @if($t->status_pesanan === 'Selesai')
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; background: #E8F5EE; color: var(--color-primary);">SUKSES</span>
                    @elseif($t->status_pesanan === 'Menunggu')
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; background: #FEF9EE; color: var(--color-warning);">PENDING</span>
                    @else
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; background: #FEF0EF; color: var(--color-danger);">BATAL</span>
                    @endif
                </td>
                <td style="padding: 12px; text-align: right;">
                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                        @if($t->status_pesanan === 'Menunggu')
                            <form method="POST" action="{{ route('transaksi.konfirmasi', $t) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="ACC" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #B7E4C7; background: #E8F5EE; color: var(--color-primary); cursor: pointer; font-size: 10px; font-weight: 700;">ACC</button>
                            </form>
                            <form method="POST" action="{{ route('transaksi.batalkan', $t) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="TOLAK" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #F5B7B1; background: #FEF0EF; color: #CB4335; cursor: pointer; font-size: 10px; font-weight: 700;">TOLAK</button>
                            </form>
                        @endif

                        @if($t->tipe_transaksi !== 'ecommerce')
                            <a href="{{ route('transaksi.edit', $t) }}" title="Edit" 
                               style="padding: 4px 8px; border-radius: 4px; border: 1px solid #AED6F1; background: #EBF5FB; color: #2E86C1; text-decoration: none; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center;">
                               EDIT
                            </a>

                            <form method="POST" action="{{ route('transaksi.destroy', $t) }}" onsubmit="return confirm('Hapus transaksi ini? (Stok akan dikembalikan jika status Selesai)');">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #FADBD8; background: #FDEDEC; color: #CB4335; cursor: pointer; font-size: 10px; font-weight: 700;">HAPUS</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 30px; text-align: center; color: var(--color-muted); font-size: 13px;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
