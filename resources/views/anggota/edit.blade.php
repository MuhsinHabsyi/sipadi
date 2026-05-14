<x-sipadi-layout title="Edit Anggota Petani">

<div class="card" style="max-width: 720px; margin: 0 auto;">
    <div class="card-header" style="border-bottom: 1px solid var(--color-border); padding-bottom: 16px; margin-bottom: 24px;">
        <h2 class="card-title" style="font-size: 20px;">Edit Data Anggota Tani</h2>
        <p style="font-size: 13px; color: var(--color-muted); margin-top: 4px;">Perbarui data identitas diri dan detail lahan garapan petani di bawah ini.</p>
    </div>

    <form method="POST" action="{{ route('anggota.update', $anggota) }}">
        @csrf @method('PUT')

        {{-- BAGIAN 1: IDENTITAS DATA DIRI --}}
        <div style="margin-bottom: 32px; padding: 20px; background: #F8FAFC; border-radius: var(--radius-md); border: 1px solid #E2E8F0;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <div style="width: 28px; height: 28px; background: var(--color-primary); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold;">1</div>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--color-accent);">Identitas Data Diri</h3>
            </div>

            {{-- Row: Nama & Umur --}}
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">
                        Nama Lengkap <span style="color: var(--color-danger);">*</span>
                    </label>
                    <input type="text" name="nama_petani" value="{{ old('nama_petani', $anggota->nama_petani) }}" required autofocus
                           style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                    @error('nama_petani') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">Umur</label>
                    <input type="number" name="umur" value="{{ old('umur', $anggota->umur) }}" min="1" max="150"
                           style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                    @error('umur') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- No. HP --}}
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">No. Handphone (HP)</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $anggota->no_hp) }}"
                       style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                @error('no_hp') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">Alamat Tempat Tinggal</label>
                <textarea name="alamat" rows="3"
                          style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white; resize: vertical;">{{ old('alamat', $anggota->alamat) }}</textarea>
                @error('alamat') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- BAGIAN 2: DETAIL LAHAN GARAPAN --}}
        <div style="margin-bottom: 32px; padding: 20px; background: linear-gradient(135deg, #F0F7F4, #E2EFEA); border-radius: var(--radius-md); border: 1px solid #B7E4C7;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <div style="width: 28px; height: 28px; background: var(--color-primary); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold;">2</div>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--color-accent);">Detail Lahan Garapan</h3>
            </div>

            {{-- Row: Nama Lahan & Status Lahan --}}
            <div style="display: grid; grid-template-columns: 3fr 2fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">Nama Lahan / Blok</label>
                    <input type="text" name="nama_lahan" value="{{ old('nama_lahan', $anggota->nama_lahan) }}"
                           style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                    @error('nama_lahan') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">Status Lahan</label>
                    <select name="status_lahan"
                            style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                        <option value="">— Pilih Status —</option>
                        @foreach(\App\Models\AnggotaPetani::STATUS_LAHAN_OPTIONS as $key => $label)
                            <option value="{{ $key }}" {{ old('status_lahan', $anggota->status_lahan) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status_lahan') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Luas Lahan --}}
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">
                    Luas Lahan (tumbak) <span style="color: var(--color-danger);">*</span>
                </label>
                <input type="number" step="0.1" name="luas_lahan" value="{{ old('luas_lahan', $anggota->luas_lahan) }}" required
                       style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                @error('luas_lahan') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            {{-- Alamat Lahan --}}
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--color-text);">Alamat Lokasi Lahan</label>
                <textarea name="alamat_lahan" rows="2"
                          style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white; resize: vertical;">{{ old('alamat_lahan', $anggota->alamat_lahan) }}</textarea>
                @error('alamat_lahan') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Tombol --}}
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('anggota.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity .2s;" onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">Perbarui Data Anggota</button>
        </div>
    </form>
</div>

</x-sipadi-layout>
