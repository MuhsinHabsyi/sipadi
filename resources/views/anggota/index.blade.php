<x-sipadi-layout title="Data Anggota Petani">

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Anggota Kelompok Tani</h2>
        <a href="{{ route('anggota.create') }}" style="background: var(--color-primary); color: black; padding: 8px 16px; border-radius: var(--radius-md); text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ph-bold ph-user-plus"></i> Tambah Anggota
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="border-bottom: 2px solid var(--color-border);">
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">No</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Nama Petani</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Kontak &amp; Alamat</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Lahan Garapan</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Status Lahan</th>
                <th style="padding: 12px; text-align: left; font-size: 13px; color: var(--color-muted);">Luas (tumbak)</th>
                <th style="padding: 12px; text-align: center; font-size: 13px; color: var(--color-muted);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggotas as $i => $anggota)
            <tr style="border-bottom: 1px solid var(--color-border); transition: background .2s;" onmouseover="this.style.background='#F8FAF9'" onmouseout="this.style.background='transparent'">
                <td style="padding: 12px; font-size: 14px; color: var(--color-muted);">{{ $i + 1 }}</td>
                <td style="padding: 12px;">
                    <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $anggota->nama_petani }}</div>
                    @if($anggota->umur)
                        <div style="font-size: 11px; color: var(--color-muted);">Umur: {{ $anggota->umur }} tahun</div>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <div style="font-size: 13px; color: var(--color-text);">
                        <i class="ph-bold ph-phone" style="color: var(--color-muted);"></i> {{ $anggota->no_hp ?? '—' }}
                    </div>
                    <div style="font-size: 12px; color: var(--color-muted); margin-top: 2px;">{{ $anggota->alamat ?? '—' }}</div>
                </td>
                <td style="padding: 12px;">
                    <div style="font-size: 13px; font-weight: 500; color: var(--color-text);">{{ $anggota->nama_lahan ?? '—' }}</div>
                    @if($anggota->alamat_lahan)
                        <div style="font-size: 11px; color: var(--color-muted);">{{ $anggota->alamat_lahan }}</div>
                    @endif
                </td>
                <td style="padding: 12px; font-size: 13px;">
                    @if($anggota->status_lahan === 'Lahan Milik')
                        <span style="background: #E8F5EE; color: var(--color-primary); padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600;">Milik Sendiri</span>
                    @elseif($anggota->status_lahan === 'Lahan Sewa')
                        <span style="background: #FEF9EE; color: var(--color-warning); padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600;">Sewa</span>
                    @else
                        <span style="color: var(--color-muted); font-style: italic;">—</span>
                    @endif
                </td>
                <td style="padding: 12px; font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $anggota->luas_lahan }}</td>
                <td style="padding: 12px; text-align: center;">
                    <div style="display: inline-flex; gap: 6px;">
                        <a href="{{ route('anggota.edit', $anggota) }}" title="Edit"
                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; background: #EBF5FB; color: var(--color-info); text-decoration: none; transition: all .2s;">
                            <i class="ph-bold ph-pencil-simple" style="font-size: 15px;"></i>
                        </a>
                        <form method="POST" action="{{ route('anggota.destroy', $anggota) }}" onsubmit="return confirm('Yakin ingin menghapus data anggota ini?');" style="display: inline;">
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
                <td colspan="7" style="padding: 40px; text-align: center; color: var(--color-muted);">
                    <i class="ph-bold ph-user-list" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    Belum ada data anggota petani.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</x-sipadi-layout>
