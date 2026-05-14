<x-sipadi-layout title="Pengadaan Bibit & Pupuk">

<div class="card" style="margin: 0 auto;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="card-title"><i class="ph-bold ph-shopping-cart"></i> Data Pengadaan Bibit & Pupuk</h2>
            <p style="font-size: 13px; color: var(--color-muted); margin-top: 4px;">Kelola riwayat pengadaan barang masuk. Pembelian akan masuk ke arus kas pengeluaran.</p>
        </div>
        <a href="{{ route('pengadaan.create') }}" style="background: var(--color-primary); color: black; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-plus"></i> Tambah Pengadaan
        </a>
    </div>

    <div style="overflow-x: auto; padding: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border);">
                    <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--color-muted);">Tanggal</th>
                    <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--color-muted);">Jenis</th>
                    <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--color-muted);">Asal</th>
                    <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--color-muted);">Jumlah</th>
                    <th style="padding: 12px; text-align: right; font-size: 12px; color: var(--color-muted);">Total Biaya</th>
                    <th style="padding: 12px; text-align: center; font-size: 12px; color: var(--color-muted);">Keadaan</th>
                    <th style="padding: 12px; text-align: right; font-size: 12px; color: var(--color-muted);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengadaans as $item)
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <td style="padding: 12px; font-size: 13px;">{{ $item->created_at->format('d M Y') }}</td>
                    <td style="padding: 12px; font-size: 13px; font-weight: 700;">{{ $item->jenis_barang }}</td>
                    <td style="padding: 12px;">
                        @if($item->asal_barang === 'Beli')
                            <span style="background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">{{ $item->asal_barang }}</span>
                        @else
                            <span style="background: #E0F2FE; color: #0369A1; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">{{ $item->asal_barang }}</span>
                        @endif
                    </td>
                    <td style="padding: 12px; font-size: 13px;">{{ $item->jumlah }}</td>
                    <td style="padding: 12px; text-align: right; font-size: 13px; font-weight: 700; color: {{ $item->total_biaya > 0 ? '#CB4335' : 'var(--color-muted)' }};">
                        Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                    </td>
                    <td style="padding: 12px; text-align: center; font-size: 12px; color: var(--color-muted);">
                        {{ $item->keadaan ?? '-' }}
                    </td>
                    <td style="padding: 12px; text-align: right;">
                        <form method="POST" action="{{ route('pengadaan.destroy', $item) }}" onsubmit="return confirm('Hapus pengadaan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #FADBD8; background: #FDEDEC; color: #CB4335; cursor: pointer; font-size: 10px; font-weight: 700;">HAPUS</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: var(--color-muted);">Belum ada data pengadaan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-sipadi-layout>
