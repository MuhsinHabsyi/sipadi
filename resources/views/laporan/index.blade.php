<x-sipadi-layout title="Laporan Operasional">

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Laporan Operasional</h2>
        @if(in_array(session('pengguna_role'), ['staf_keuangan', 'ketua']))
        <a href="{{ route('laporan.create') }}" style="background: var(--color-primary); color: black; padding: 8px 16px; border-radius: var(--radius-md); text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-plus"></i> Buat Laporan Baru
        </a>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">ID</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Musim Tanam</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Tanggal Dibuat</th>
                <th style="padding: 12px; text-align: center; font-size: 13px; color: var(--color-muted);">Status</th>
                <th style="padding: 12px; text-align: right; font-size: 13px; color: var(--color-muted);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $laporan)
            <tr style="border-bottom: 1px solid var(--color-border); transition: background .2s;">
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">#{{ $laporan->id }}</td>
                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $laporan->musim_tanam }}</td>
                <td style="padding: 12px; font-size: 13px; color: var(--color-muted);">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}</td>
                <td style="padding: 12px; text-align: center;">
                    @if($laporan->status_finalisasi)
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #E8F5EE; color: var(--color-primary);">Final</span>
                    @else
                        <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: #FEF9EE; color: var(--color-warning);">Draft</span>
                    @endif
                </td>
                <td style="padding: 12px; text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('laporan.show', $laporan) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; background: #EBF5FB; color: var(--color-info); text-decoration: none; font-size: 13px; font-weight: 600;" title="Cetak Laporan">
                            <i class="ph-bold ph-printer"></i> Cetak
                        </a>
                        <form method="POST" action="{{ route('laporan.destroy', $laporan) }}" onsubmit="return confirm('Hapus laporan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="padding: 6px 12px; border-radius: 8px; background: #FEF0EF; color: #CB4335; border: none; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="ph-bold ph-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 40px; text-align: center; color: var(--color-muted);">
                    <i class="ph-bold ph-file-text" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    Belum ada laporan yang dibuat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</x-sipadi-layout>
