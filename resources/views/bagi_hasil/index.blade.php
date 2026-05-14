<x-sipadi-layout title="Pembagian Hasil">

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Rekapitulasi Pembagian Hasil per Musim</h2>
        <a href="{{ route('bagi-hasil.create') }}" style="background: var(--color-primary); color: black; padding: 8px 16px; border-radius: var(--radius-md); text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-calculator"></i> Kalkulasi Baru
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Musim Tanam</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Jumlah Petani</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Total Keuntungan Kelompok</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Waktu Kalkulasi</th>
                <th style="padding: 12px; text-align: center; font-size: 13px; color: var(--color-muted);">Status Laporan</th>
                <th style="padding: 12px; text-align: right; font-size: 13px; color: var(--color-muted);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapMusim as $rekap)
            <tr style="border-bottom: 1px solid var(--color-border); transition: background .2s;">
                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $rekap['musim_tanam'] }}</td>
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">{{ $rekap['jumlah_petani'] }} orang</td>
                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: var(--color-primary);">Rp {{ number_format($rekap['total_keuntungan'], 0, ',', '.') }}</td>
                <td style="padding: 12px; font-size: 13px; color: var(--color-muted);">{{ \Carbon\Carbon::parse($rekap['tanggal_dibagi'])->format('d M Y H:i') }}</td>
                <td style="padding: 12px; text-align: center;">
                    @php 
                        // Cek status laporan untuk musim ini
                        $laporan = \App\Models\LaporanOperasional::where('musim_tanam', $rekap['musim_tanam'])->first();
                    @endphp
                    @if($laporan)
                        @if($laporan->status_finalisasi)
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #E8F5EE; color: var(--color-primary);">Laporan Final</span>
                        @else
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #FEF9EE; color: var(--color-warning);">Laporan Draft</span>
                        @endif
                    @else
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #F0F4F8; color: var(--color-muted);">Belum Dibuat</span>
                    @endif
                </td>
                <td style="padding: 12px; text-align: right;">
                    <form method="POST" action="{{ route('bagi-hasil.destroyMusim') }}" onsubmit="return confirm('Hapus SELURUH data pembagian hasil musim ini?');">
                        @csrf @method('DELETE')
                        <input type="hidden" name="musim_tanam" value="{{ $rekap['musim_tanam'] }}">
                        <button type="submit" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #FADBD8; background: #FDEDEC; color: #CB4335; cursor: pointer; font-size: 10px; font-weight: 700;">HAPUS MUSIM</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 40px; text-align: center; color: var(--color-muted);">
                    <i class="ph-bold ph-chart-pie-slice" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    Belum ada riwayat pembagian hasil.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h2 class="card-title">Rincian Pembagian Hasil (Keseluruhan)</h2>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Petani</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Musim</th>
                <th style="padding: 12px; text-align: center; font-size: 13px; color: var(--color-muted);">Proporsi Panen</th>
                <th style="padding: 12px; text-align: right; font-size: 13px; color: var(--color-muted);">Alokasi Diterima</th>
                <th style="padding: 12px; text-align: right; font-size: 13px; color: var(--color-muted);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bagiHasils as $bagi)
            <tr style="border-bottom: 1px solid var(--color-border);">
                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $bagi->petani->nama_petani ?? 'Unknown' }}</td>
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">{{ $bagi->musim_tanam }}</td>
                <td style="padding: 12px; font-size: 14px; text-align: center; color: var(--color-text);">{{ number_format($bagi->proporsi_panen, 2, ',', '.') }}%</td>
                <td style="padding: 12px; font-size: 14px; font-weight: 600; text-align: right; color: var(--color-primary);">Rp {{ number_format($bagi->alokasi_keuntungan, 0, ',', '.') }}</td>
                <td style="padding: 12px; text-align: right;">
                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                        <a href="{{ route('bagi-hasil.edit', $bagi) }}" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #AED6F1; background: #EBF5FB; color: #2E86C1; text-decoration: none; font-size: 10px; font-weight: 700;">EDIT</a>
                        <form method="POST" action="{{ route('bagi-hasil.destroy', $bagi) }}" onsubmit="return confirm('Hapus catatan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #FADBD8; background: #FDEDEC; color: #CB4335; cursor: pointer; font-size: 10px; font-weight: 700;">HAPUS</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 20px; text-align: center; color: var(--color-muted); font-size: 13.5px;">Data kosong</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</x-sipadi-layout>
