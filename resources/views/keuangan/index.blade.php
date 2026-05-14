<x-sipadi-layout title="Arus Kas Keuangan">

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #E8F5EE; color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 24px;">
            <i class="ph-bold ph-arrow-down-left"></i>
        </div>
        <div>
            <div style="font-size: 13px; color: var(--color-muted); font-weight: 600;">Total Pemasukan</div>
            <div style="font-size: 20px; font-weight: 800; color: var(--color-text);">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
        </div>
    </div>
    
    <div class="card" style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #FEF0EF; color: var(--color-danger); display: flex; align-items: center; justify-content: center; font-size: 24px;">
            <i class="ph-bold ph-arrow-up-right"></i>
        </div>
        <div>
            <div style="font-size: 13px; color: var(--color-muted); font-weight: 600;">Total Pengeluaran</div>
            <div style="font-size: 20px; font-weight: 800; color: var(--color-text);">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 16px; background: linear-gradient(135deg, var(--color-accent), var(--color-primary)); color: black;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 24px;">
            <i class="ph-bold ph-wallet"></i>
        </div>
        <div>
            <div style="font-size: 13px; opacity: 0.8; font-weight: 600;">Saldo Saat Ini</div>
            <div style="font-size: 20px; font-weight: 800;">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Riwayat Arus Kas</h2>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Tanggal</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Musim Tanam</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Keterangan</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Jenis</th>
                <th style="padding: 12px; text-align: right; font-size: 13px; color: var(--color-muted);">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($arusKas as $kas)
            <tr style="border-bottom: 1px solid var(--color-border); transition: background .2s;">
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">{{ \Carbon\Carbon::parse($kas->tanggal)->format('d M Y') }}</td>
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">{{ $kas->musim_tanam ?? '-' }}</td>
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">{{ $kas->keterangan }}</td>
                <td style="padding: 12px;">
                    @if($kas->jenis_transaksi === 'pemasukan')
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #E8F5EE; color: var(--color-primary);">Pemasukan</span>
                    @else
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #FEF0EF; color: var(--color-danger);">Pengeluaran</span>
                    @endif
                </td>
                <td style="padding: 12px; text-align: right; font-size: 14px; font-weight: 600; color: {{ $kas->jenis_transaksi === 'pemasukan' ? 'var(--color-primary)' : 'var(--color-danger)' }};">
                    {{ $kas->jenis_transaksi === 'pemasukan' ? '+' : '-' }} {{ number_format($kas->nominal, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 40px; text-align: center; color: var(--color-muted);">
                    <i class="ph-bold ph-money" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    Belum ada data arus kas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</x-sipadi-layout>
