<x-sipadi-layout title="Katalog & Landing">

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title"><i class="ph-bold ph-storefront"></i> Kelola Katalog Beras</h2>
        <p style="font-size: 13px; color: var(--color-muted); margin-top: 4px;">Data ini akan ditampilkan pada halaman depan pelanggan (landing page). Jika data kosong, card produk tidak akan tampil.</p>
    </div>

    <form method="POST" action="{{ route('transaksi.konten.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Bagian Hero --}}
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--color-primary); margin-bottom: 16px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                <i class="ph-bold ph-megaphone"></i> Bagian Hero (Atas)
            </h3>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Judul Utama</label>
                <input type="text" name="judul_hero" value="{{ old('judul_hero', $konten->judul_hero ?? '') }}" required
                       style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Deskripsi Singkat</label>
                <textarea name="deskripsi_hero" rows="3" required
                          style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; resize: vertical;">{{ old('deskripsi_hero', $konten->deskripsi_hero ?? '') }}</textarea>
            </div>
        </div>

        {{-- Bagian Katalog --}}
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--color-primary); margin-bottom: 16px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                <i class="ph-bold ph-shopping-cart"></i> Bagian Katalog Produk
            </h3>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Judul Seksi Katalog</label>
                <input type="text" name="judul_toko" value="{{ old('judul_toko', $konten->judul_toko ?? '') }}" required
                       style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Deskripsi Seksi Katalog</label>
                <textarea name="deskripsi_toko" rows="2" required
                          style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 8px; font-family: inherit; resize: vertical;">{{ old('deskripsi_toko', $konten->deskripsi_toko ?? '') }}</textarea>
            </div>
        </div>

        {{-- Card Produk Utama --}}
        <div style="margin-bottom: 24px; background: linear-gradient(135deg, #FAFCFB, #F4F9F6); padding: 22px; border-radius: 14px; border: 1px solid #D4EDE0;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid #E2EFE7; padding-bottom: 10px;">
                <h3 style="font-size: 15px; font-weight: 800; color: #1B4332; display: flex; align-items: center; gap: 8px; margin: 0;">
                    <i class="ph-bold ph-package" style="font-size: 18px; color: var(--color-primary);"></i> Card Produk Beras (Tampilan Pelanggan)
                </h3>
            </div>

            {{-- Upload Gambar --}}
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1B4332; margin-bottom: 6px;">Gambar Produk</label>
                @if(($konten->produk_gambar ?? null))
                    <div style="margin-bottom: 8px;">
                        <img src="{{ asset('storage/' . $konten->produk_gambar) }}" alt="Gambar Produk" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid var(--color-border); object-fit: cover;">
                        <span style="display: block; font-size: 11px; color: var(--color-muted); margin-top: 4px;">Gambar saat ini</span>
                    </div>
                @endif
                <input type="file" name="produk_gambar" accept="image/*"
                       style="width: 100%; padding: 8px; border: 1px dashed #C4D9CC; border-radius: 8px; font-family: inherit; font-size: 13px; background: #fff;">
                <span style="font-size: 11px; color: var(--color-muted); display: block; margin-top: 4px;">Format: JPG, PNG, WebP. Maks: 2MB</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #1B4332; margin-bottom: 6px;">Judul / Nama Beras</label>
                    <input type="text" name="produk_nama" value="{{ old('produk_nama', $konten->produk_nama ?? '') }}"
                           placeholder="Contoh: Beras Pandan Wangi"
                           style="width: 100%; padding: 10px; border: 1px solid #C4D9CC; border-radius: 8px; font-family: inherit; font-size: 13.5px; background: #fff;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #1B4332; margin-bottom: 6px;">Harga</label>
                    <input type="number" name="produk_harga" value="{{ old('produk_harga', $konten->produk_harga ?? '') }}" min="0"
                           placeholder="Contoh: 15000"
                           style="width: 100%; padding: 10px; border: 1px solid #C4D9CC; border-radius: 8px; font-family: inherit; font-size: 13.5px; background: #fff;">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1B4332; margin-bottom: 6px;">Deskripsi Singkat Beras</label>
                <textarea name="produk_deskripsi" rows="2"
                          placeholder="Contoh: Beras premium dengan aroma pandan alami..."
                          style="width: 100%; padding: 10px; border: 1px solid #C4D9CC; border-radius: 8px; font-family: inherit; font-size: 13.5px; background: #fff; resize: vertical;">{{ old('produk_deskripsi', $konten->produk_deskripsi ?? '') }}</textarea>
            </div>

            <div style="background: #fff; padding: 16px; border-radius: 10px; border: 1px solid #D4EDE0; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #1B4332; margin-bottom: 2px;">Stok</label>
                    <span style="font-size: 11.5px; color: var(--color-muted);">Harus ≤ Stok E-Commerce saat ini.</span>
                    @php $stokEcommerce = $stok ? $stok->stok_ecommerce : 0; @endphp
                    <div style="margin-top: 6px; font-size: 12px; font-weight: 600; color: #2E86C1; display: flex; align-items: center; gap: 4px;">
                        <i class="ph-bold ph-shopping-bag"></i> Stok E-Commerce tersedia: <span style="background: #EBF5FB; padding: 2px 6px; border-radius: 4px; font-weight: 800;">{{ number_format($stokEcommerce, 0, ',', '.') }} kg</span>
                    </div>
                </div>
                <div style="width: 150px; flex-shrink: 0;">
                    <input type="number" name="produk_stok_dijual" value="{{ old('produk_stok_dijual', $konten->produk_stok_dijual ?? 0) }}" min="0"
                           style="width: 100%; padding: 10px; border: 2px solid var(--color-primary); border-radius: 8px; font-family: inherit; font-size: 15px; font-weight: 700; color: var(--color-primary); text-align: center; background: #FAFDFB;">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
            <a href="{{ route('transaksi.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer;">
                <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

</x-sipadi-layout>
