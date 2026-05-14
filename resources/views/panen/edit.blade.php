<x-sipadi-layout title="Edit Data Panen">

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Form Edit Hasil Panen</h2>
    </div>

    <!-- Info Petani (Read Only di Edit) -->
    <div style="margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--color-border);">
        <label style="display: block; font-size: 11px; font-weight: 700; color: var(--color-muted); text-transform: uppercase; margin-bottom: 4px;">Nama Petani</label>
        <div style="font-size: 16px; font-weight: 700; color: var(--color-text);">{{ $selectedPetani->nama_petani }}</div>
        <div style="font-size: 12px; color: var(--color-muted);">ID Petani: #{{ $selectedPetani->id }}</div>
    </div>

    <form method="POST" action="{{ route('panen.update', $panen) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="id_petani" value="{{ $selectedPetani->id }}">

        {{-- Detail Lahan Garapan & Estimasi Panen --}}
        <div style="margin-bottom: 24px; padding: 20px; background: linear-gradient(135deg, #F0F7F4, #E2EFEA); border-radius: var(--radius-md); border: 1px solid #B7E4C7;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <div style="width: 24px; height: 24px; background: var(--color-primary); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                    <i class="ph-bold ph-map-pin"></i>
                </div>
                <h3 style="font-size: 14px; font-weight: 700; color: var(--color-accent); margin: 0;">Detail Lahan Garapan</h3>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: var(--color-primary); text-transform: uppercase; margin-bottom: 4px;">Nama Lahan</div>
                    <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $selectedPetani->nama_lahan ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: var(--color-primary); text-transform: uppercase; margin-bottom: 4px;">Status Lahan</div>
                    <div style="font-size: 14px; font-weight: 600; color: var(--color-text);">{{ $selectedPetani->status_lahan ?? '—' }}</div>
                </div>
                <div style="grid-column: span 2; background: white; padding: 12px; border-radius: 8px; border: 1px solid #B7E4C7; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: var(--color-muted); text-transform: uppercase;">Luas Lahan</div>
                        <div style="font-size: 15px; font-weight: 700; color: var(--color-text);">{{ $selectedPetani->luas_lahan }} <span style="font-weight: normal; font-size: 12px;">tumbak</span></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--color-primary); text-transform: uppercase;">Estimasi Panen</div>
                        <div style="font-size: 18px; font-weight: 800; color: var(--color-accent);">{{ number_format($selectedPetani->luas_lahan * 10, 0, ',', '.') }} <span style="font-weight: normal; font-size: 12px;">KG</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Musim Tanam</label>
            <input type="text" name="musim_tanam" value="{{ old('musim_tanam', $panen->musim_tanam) }}" readonly
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: #F4F6F9; cursor: not-allowed;">
            @error('musim_tanam') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Total Panen (kg)</label>
            <input type="number" step="0.1" name="total_panen" value="{{ old('total_panen', $panen->total_panen) }}" required
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ session('error') ? 'var(--color-danger)' : 'var(--color-border)' }}; border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
            @error('total_panen') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        @if(session('error'))
        <div style="margin-bottom: 20px; background: #FEF9EE; border: 1px solid #FAD7A1; padding: 12px; border-radius: var(--radius-md);">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: var(--color-warning); cursor: pointer;">
                <input type="checkbox" name="confirm_anomaly" value="1" style="width: 16px; height: 16px;">
                Saya mengkonfirmasi bahwa data panen ini sudah benar.
            </label>
        </div>
        @endif

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('panen.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer;">Update Data Panen</button>
        </div>
    </form>
</div>

</x-sipadi-layout>
