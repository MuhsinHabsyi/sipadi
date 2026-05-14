<x-sipadi-layout title="Edit Alokasi Bibit">

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Form Edit Alokasi Bibit</h2>
    </div>

    <form method="POST" action="{{ route('pertanian.update', $pertanian) }}">
        @csrf
        @method('PUT')

        {{-- Nama Petani — Dropdown --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Nama Petani <span style="color: var(--color-danger);">*</span>
            </label>
            <select name="id_petani" id="select-petani" required
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                <option value="">— Pilih Petani —</option>
                @foreach($petanis as $petani)
                    <option value="{{ $petani->id }}" data-luas="{{ $petani->luas_lahan }}" {{ old('id_petani', $pertanian->id_petani) == $petani->id ? 'selected' : '' }}>
                        {{ $petani->nama_petani }}
                    </option>
                @endforeach
            </select>
            @error('id_petani') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Luas Lahan — Read Only / Auto-Populate --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Luas Lahan (tumbak)</label>
            <input type="text" id="luas-lahan" readonly
                   placeholder="Otomatis terisi saat memilih petani"
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: #F4F6F9; color: var(--color-text); cursor: not-allowed;">
        </div>

        {{-- Musim Tanam --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Musim Tanam <span style="color: var(--color-danger);">*</span>
            </label>
            <select name="musim_tanam" required
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                <option value="">— Pilih Musim Tanam —</option>
                <option value="Musim Tanam Utama (Nov-Mar)" {{ old('musim_tanam', $pertanian->musim_tanam) == 'Musim Tanam Utama (Nov-Mar)' ? 'selected' : '' }}>Musim Tanam Utama (Nov-Mar)</option>
                <option value="Musim Tanam Gadu (Apr-Jul)" {{ old('musim_tanam', $pertanian->musim_tanam) == 'Musim Tanam Gadu (Apr-Jul)' ? 'selected' : '' }}>Musim Tanam Gadu (Apr-Jul)</option>
                <option value="Musim Tanam Kemarau (Agu-Okt)" {{ old('musim_tanam', $pertanian->musim_tanam) == 'Musim Tanam Kemarau (Agu-Okt)' ? 'selected' : '' }}>Musim Tanam Kemarau (Agu-Okt)</option>
            </select>
            @error('musim_tanam') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Jumlah Bibit (Otomatis berdasarkan Luas Lahan) --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Jumlah Bibit (kg) <span style="color: var(--color-danger);">*</span>
            </label>
            <input type="number" step="0.01" name="jumlah_bibit" id="input-bibit" value="{{ old('jumlah_bibit', $pertanian->jumlah_bibit) }}" required readonly
                   placeholder="Otomatis terhitung (0,5 kg per tumbak)"
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: #F4F6F9; color: var(--color-text); cursor: not-allowed; font-weight: 600;">
            <span style="font-size: 11px; color: var(--color-muted); margin-top: 4px; display: block;">
                <i class="ph-bold ph-calculator" style="font-size: 12px;"></i> Rumus: Luas Lahan × 0,5 kg
            </span>
            @error('jumlah_bibit') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Panel Estimasi Hasil Panen --}}
        <div id="panel-estimasi" style="display: none; margin-bottom: 24px; padding: 16px 20px; background: linear-gradient(135deg, #E8F5EE, #D4EDE0); border: 1px solid #A8D5B5; border-radius: var(--radius-md); box-shadow: 0 4px 12px rgba(45,106,79,0.08);">
            <div style="display: flex; align-items: flex-start; gap: 14px;">
                <div style="width: 42px; height: 42px; background: var(--color-primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; flex-shrink: 0;">
                    <i class="ph-bold ph-plant"></i>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 11px; font-weight: 700; color: var(--color-primary); text-transform: uppercase;">Estimasi Hasil Panen</span>
                    </div>
                    <div id="estimasi-nilai" style="font-size: 20px; font-weight: 800; color: var(--color-accent); margin-top: 4px;">0 KG</div>
                    <div style="font-size: 12px; color: #3B5A4B; margin-top: 4px;">
                        Berdasarkan luas lahan <strong id="label-luas">0</strong> tumbak.
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('pertanian.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer;">Update Alokasi</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectPetani  = document.getElementById('select-petani');
        const luasLahan     = document.getElementById('luas-lahan');
        const inputBibit    = document.getElementById('input-bibit');
        const panelEstimasi = document.getElementById('panel-estimasi');
        const estimasiNilai = document.getElementById('estimasi-nilai');
        const labelLuas     = document.getElementById('label-luas');

        if (!selectPetani || !luasLahan) return;

        let currentLuas = 0;

        function hitungEstimasi() {
            if (currentLuas > 0) {
                const kebutuhanBibit = currentLuas * 0.5;
                const estimasiPanen  = currentLuas * 10;

                if (inputBibit) {
                    inputBibit.value = kebutuhanBibit;
                }

                labelLuas.textContent = currentLuas;
                estimasiNilai.textContent = `${estimasiPanen.toLocaleString('id-ID')} KG`;
                panelEstimasi.style.display = 'block';
            } else {
                if (inputBibit) inputBibit.value = '';
                panelEstimasi.style.display = 'none';
            }
        }

        function updateLuasLahan() {
            const selected = selectPetani.options[selectPetani.selectedIndex];
            if (selected && selected.value) {
                const luasStr = selected.getAttribute('data-luas');
                currentLuas = parseFloat(luasStr) || 0;
                luasLahan.value = luasStr + ' tumbak';
            } else {
                currentLuas = 0;
                luasLahan.value = '';
            }
            hitungEstimasi();
        }

        selectPetani.addEventListener('change', updateLuasLahan);

        if (selectPetani.value) {
            updateLuasLahan();
        }
    });
</script>
@endpush

</x-sipadi-layout>
