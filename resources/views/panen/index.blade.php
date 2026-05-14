<x-sipadi-layout title="Data Panen">

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Hasil Panen Petani</h2>
        <a href="{{ route('panen.create') }}" style="background: var(--color-primary); color: black; padding: 8px 16px; border-radius: var(--radius-md); text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-plus"></i> Tambah Data Panen
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">ID Panen</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Nama Petani</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Musim Tanam</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Total Panen (kg)</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Waktu Catat</th>
                <th style="padding: 12px; text-align: center; font-size: 13px; color: var(--color-muted);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($panens as $panen)
            <tr style="border-bottom: 1px solid var(--color-border); transition: background .2s;" onmouseover="this.style.background='#F8FAF9'" onmouseout="this.style.background='transparent'">
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">#{{ $panen->id }}</td>
                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $panen->petani->nama_petani ?? 'Tidak diketahui' }}</td>
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">{{ $panen->musim_tanam }}</td>
                <td style="padding: 12px; font-size: 14px; font-weight: 700; color: var(--color-primary);">{{ number_format($panen->total_panen, 1, ',', '.') }} kg</td>
                <td style="padding: 12px; font-size: 13px; color: var(--color-muted);">{{ \Carbon\Carbon::parse($panen->created_at)->format('d M Y H:i') }}</td>
                <td style="padding: 12px; text-align: center;">
                    <div style="display: inline-flex; gap: 6px;">
                        <a href="{{ route('panen.edit', $panen) }}" title="Edit"
                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; background: #EBF5FB; color: var(--color-info); text-decoration: none; transition: all .2s;">
                            <i class="ph-bold ph-pencil-simple" style="font-size: 15px;"></i>
                        </a>
                        <form method="POST" action="{{ route('panen.destroy', $panen) }}" onsubmit="return confirm('Yakin ingin menghapus data panen ini?');" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus"
                                    style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; background: #FEF0EF; color: var(--color-danger); border: none; cursor: pointer; transition: all .2s;">
                                <i class="ph-bold ph-trash" style="font-size: 15px;"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 40px; text-align: center; color: var(--color-muted);">
                    <i class="ph-bold ph-basket" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    Belum ada data panen yang dicatat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</x-sipadi-layout>
