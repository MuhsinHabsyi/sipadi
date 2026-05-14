<x-sipadi-layout title="Edit Alokasi Bagi Hasil">

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Edit Alokasi Keuntungan</h2>
    </div>

    <form method="POST" action="{{ route('bagi-hasil.update', $bagiHasil) }}">
        @csrf
        @method('PATCH')

        <div style="margin-bottom: 20px;">
            <label style="font-size: 13px; font-weight: 600; color: var(--color-muted);">Petani</label>
            <div style="font-size: 16px; font-weight: 700;">{{ $bagiHasil->petani->nama_petani ?? 'Unknown' }}</div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="font-size: 13px; font-weight: 600; color: var(--color-muted);">Musim Tanam</label>
            <div style="font-size: 14px;">{{ $bagiHasil->musim_tanam }}</div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Alokasi Keuntungan (Rp)
            </label>
            <input type="number" name="alokasi_keuntungan" value="{{ old('alokasi_keuntungan', $bagiHasil->alokasi_keuntungan) }}" required
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 16px; font-weight: 700; color: var(--color-primary);">
            <p style="font-size: 11px; color: var(--color-muted); margin-top: 6px;">Proporsi panen: {{ number_format($bagiHasil->proporsi_panen, 2) }}%</p>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ route('bagi-hasil.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer;">Simpan Perubahan</button>
        </div>
    </form>
</div>

</x-sipadi-layout>
