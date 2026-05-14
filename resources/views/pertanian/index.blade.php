<x-sipadi-layout title="Data Pertanian & Bibit">

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Lahan & Alokasi Bibit Petani</h2>
        <a href="{{ route('pertanian.create') }}" style="background: var(--color-primary); color: black; padding: 8px 16px; border-radius: var(--radius-md); text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-plus"></i> Tambah Data Baru
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">No</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Nama Petani</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Musim Tanam</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Alokasi Bibit</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Estimasi Hasil Panen</th>
                <th style="padding: 12px; text-align: center; font-size: 13px; color: var(--color-muted);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alokasis as $i => $a)
            <tr style="border-bottom: 1px solid var(--color-border); transition: background .2s;" onmouseover="this.style.background='#F8FAF9'" onmouseout="this.style.background='transparent'">
                <td style="padding: 12px; font-size: 14px; color: var(--color-muted);">{{ $i + 1 }}</td>
                <td style="padding: 12px;">
                    <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $a->petani->nama_petani ?? '—' }}</div>
                    <div style="font-size: 11px; color: var(--color-muted);">Luas: {{ $a->petani->luas_lahan ?? 0 }} tumbak</div>
                </td>
                <td style="padding: 12px; font-size: 14px; color: var(--color-text);">{{ $a->musim_tanam }}</td>
                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $a->jumlah_bibit }} kg</td>
                <td style="padding: 12px;">
                    @php 
                        $estimasiPanen = ($a->petani->luas_lahan ?? 0) * 10;
                    @endphp
                    <span style="display: inline-flex; align-items: center; gap: 5px; background: #E8F5EE; color: var(--color-primary); padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 12px; border: 1px solid #A8D5B5;">
                        <i class="ph-bold ph-plant" style="font-size: 13px;"></i> {{ number_format($estimasiPanen, 0, ',', '.') }} kg
                    </span>
                </td>
                <td style="padding: 12px; text-align: center;">
                    <div style="display: inline-flex; gap: 6px;">
                        <a href="{{ route('pertanian.edit', $a) }}" title="Edit"
                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; background: #EBF5FB; color: var(--color-info); text-decoration: none; transition: all .2s;">
                            <i class="ph-bold ph-pencil-simple" style="font-size: 15px;"></i>
                        </a>
                        <form method="POST" action="{{ route('pertanian.destroy', $a) }}" onsubmit="return confirm('Yakin ingin menghapus data alokasi ini?');" style="display: inline;">
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
                    <i class="ph-bold ph-plant" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    Belum ada data alokasi bibit.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</x-sipadi-layout>
