<x-toko-layout title="Beranda">

{{-- ========== HERO SECTION ========== --}}
<section id="home" style="
    background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 40%, #52B788 100%);
    padding: 120px 60px 100px; color: #fff; position: relative; overflow: hidden;
">
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; border-radius: 50%; background: rgba(82,183,136,.15);"></div>
    <div style="position: absolute; bottom: -80px; left: -80px; width: 300px; height: 300px; border-radius: 50%; background: rgba(82,183,136,.1);"></div>

    <div class="container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; position: relative; z-index: 1;">
        <div>
            <div style="display: inline-block; padding: 6px 14px; background: rgba(255,255,255,.15); border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 20px; backdrop-filter: blur(4px);">
                🌾 Langsung dari Petani
            </div>
            <h1 style="font-size: 48px; font-weight: 900; line-height: 1.15; margin-bottom: 20px; letter-spacing: -1px;">
                {!! nl2br(e($konten->judul_hero ?? 'Beras Berkualitas dari Sawah Kami ke Meja Anda')) !!}
            </h1>
            <p style="font-size: 17px; opacity: .85; margin-bottom: 36px; line-height: 1.7; max-width: 460px;">
                {{ $konten->deskripsi_hero ?? 'Kelompok Tani SIPADI menghadirkan beras pilihan yang ditanam dengan penuh dedikasi. Segar, alami, dan berkualitas tinggi.' }}
            </p>
            <div style="display: flex; gap: 14px;">
                <a href="#produk" class="btn-primary" style="padding: 14px 28px; font-size: 15px; background: #fff; color: var(--primary);">
                    <i class="ph-bold ph-shopping-bag"></i> Pesan Sekarang
                </a>
                <a href="#tentang" class="btn-outline" style="padding: 14px 28px; font-size: 15px; border-color: rgba(255,255,255,.4); color: #fff;">
                    <i class="ph-bold ph-info"></i> Tentang Kami
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ========== KENAPA BERAS KAMI ========== --}}
<section style="padding: 80px 60px; background: #fff;">
    <div class="container">
        <h2 class="section-title">Kenapa Beras Kami?</h2>
        <p class="section-subtitle">Kami berkomitmen menghadirkan beras terbaik dengan proses yang transparan dan berkelanjutan.</p>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
            @foreach([
                ['icon' => 'ph-leaf', 'title' => 'Alami & Organik', 'desc' => 'Ditanam tanpa pestisida berbahaya dengan metode pertanian berkelanjutan.'],
                ['icon' => 'ph-users-three', 'title' => 'Langsung Petani', 'desc' => 'Tanpa perantara. Harga adil untuk petani dan terjangkau untuk Anda.'],
                ['icon' => 'ph-shield-check', 'title' => 'Kualitas Terjamin', 'desc' => 'Setiap butir beras melewati kontrol kualitas ketat sebelum dikemas.'],
                ['icon' => 'ph-truck', 'title' => 'Pengiriman Cepat', 'desc' => 'Beras segar dikirim langsung dari gudang kami ke pintu rumah Anda.'],
            ] as $feature)
            <div style="text-align: center; padding: 30px 20px; border-radius: var(--rl); border: 1px solid var(--border); transition: all .3s;"
                 onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'"
                 onmouseleave="this.style.transform='none';this.style.boxShadow='none'">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: linear-gradient(135deg,#E8F5EE,#D4EDE0); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;">
                    <i class="ph-bold {{ $feature['icon'] }}"></i>
                </div>
                <div style="font-size: 15px; font-weight: 700; margin-bottom: 8px;">{{ $feature['title'] }}</div>
                <div style="font-size: 13px; color: var(--muted); line-height: 1.6;">{{ $feature['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== PRODUK ========== --}}
<section id="produk" class="section" style="background: var(--bg);">
    <div class="container">
        <h2 class="section-title">{{ $konten->judul_toko ?? 'Produk Kami' }}</h2>
        <p class="section-subtitle">{{ $konten->deskripsi_toko ?? 'Pilih beras berkualitas tinggi sesuai kebutuhan Anda.' }}</p>

        @if(count($products) > 0)
        <div style="display: flex; justify-content: center;">
            @foreach($products as $product)
            <div style="background: #fff; border-radius: var(--rl); overflow: hidden; border: 1px solid var(--border); transition: all .3s; max-width: 400px; width: 100%;"
                 onmouseenter="this.style.transform='translateY(-6px)';this.style.boxShadow='var(--shadow-lg)'"
                 onmouseleave="this.style.transform='none';this.style.boxShadow='none'">

                {{-- Product visual --}}
                <div style="height: 220px; background: linear-gradient(135deg, #E8F5EE, #D4EDE0); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    @if($product['gambar'])
                        <img src="{{ $product['gambar'] }}" alt="{{ $product['nama'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 80px;">🌾</span>
                    @endif
                </div>

                <div style="padding: 24px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <h3 style="font-size: 18px; font-weight: 700; margin: 0;">{{ $product['nama'] }}</h3>
                        <span style="font-size: 11px; font-weight: 700; background: {{ $product['stok_tersedia'] > 0 ? '#E8F5EE' : '#FEF2F2' }}; color: {{ $product['stok_tersedia'] > 0 ? 'var(--primary)' : '#991B1B' }}; padding: 3px 8px; border-radius: 12px; border: 1px solid {{ $product['stok_tersedia'] > 0 ? '#B7E4C7' : '#FCA5A5' }};">
                            Stok: {{ $product['stok_tersedia'] }} kg
                        </span>
                    </div>
                    <p style="font-size: 13px; color: var(--muted); margin-bottom: 16px; line-height: 1.6;">{{ $product['deskripsi'] }}</p>

                    <div style="display: flex; align-items: baseline; gap: 4px; margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: 800; color: var(--primary);">Rp {{ number_format($product['harga'], 0, ',', '.') }}</span>
                        <span style="font-size: 13px; color: var(--muted);">/ kg</span>
                    </div>

                    <form method="POST" action="{{ route('cart.add') }}" style="display: flex; gap: 10px; align-items: center;">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product['slug'] }}">
                        <div style="display: flex; align-items: center; border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden;">
                            <button type="button" onclick="changeQty(this, -1)" style="width: 36px; height: 38px; border: none; background: var(--bg); cursor: pointer; font-size: 16px; font-weight: 700; color: var(--muted);">−</button>
                            <input type="number" name="jumlah" value="1" min="1" max="{{ $product['stok_tersedia'] ?? 100 }}"
                                   style="width: 50px; text-align: center; border: none; font-size: 14px; font-weight: 600; font-family: inherit; outline: none;">
                            <button type="button" onclick="changeQty(this, 1)" style="width: 36px; height: 38px; border: none; background: var(--bg); cursor: pointer; font-size: 16px; font-weight: 700; color: var(--muted);">+</button>
                        </div>
                        <button type="submit" class="btn-primary" style="flex: 1; justify-content: center; padding: 10px;" {{ ($product['stok_tersedia'] ?? 0) <= 0 ? 'disabled' : '' }}>
                            <i class="ph-bold ph-shopping-cart"></i> {{ ($product['stok_tersedia'] ?? 0) <= 0 ? 'Habis' : 'Keranjang' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px; color: var(--muted);">
            <i class="ph-bold ph-package" style="font-size: 48px; display: block; margin-bottom: 16px; opacity: .4;"></i>
            <p style="font-size: 16px; font-weight: 600;">Produk belum tersedia saat ini.</p>
            <p style="font-size: 13px;">Silakan kembali lagi nanti.</p>
        </div>
        @endif
    </div>
</section>

{{-- ========== KERANJANG ========== --}}
<section id="keranjang" class="section" style="background: #fff;">
    <div class="container" style="max-width: 800px;">
        <h2 class="section-title">Keranjang Belanja</h2>

        @if(empty($cart))
            <div style="text-align: center; padding: 50px; color: var(--muted);">
                <i class="ph-bold ph-shopping-cart" style="font-size: 48px; display: block; margin-bottom: 16px; opacity: .4;"></i>
                <p style="font-size: 16px; font-weight: 500;">Keranjang Anda masih kosong.</p>
                <a href="#produk" style="color: var(--primary); font-weight: 600; margin-top: 8px; display: inline-block;">Lihat Produk →</a>
            </div>
        @else
            <div style="border: 1px solid var(--border); border-radius: var(--rl); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg); border-bottom: 2px solid var(--border);">
                            <th style="padding: 14px 16px; text-align: left; font-size: 13px; color: var(--muted);">Produk</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: var(--muted);">Harga/kg</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 13px; color: var(--muted);">Jumlah (kg)</th>
                            <th style="padding: 14px 16px; text-align: right; font-size: 13px; color: var(--muted);">Subtotal</th>
                            <th style="padding: 14px 16px; width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach($cart as $item)
                            @php $subtotal = $item['harga'] * $item['jumlah']; $grandTotal += $subtotal; @endphp
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 14px 16px; font-size: 14px; font-weight: 600;">{{ $item['nama'] }}</td>
                                <td style="padding: 14px 16px; text-align: center; font-size: 14px;">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <form method="POST" action="{{ route('cart.update') }}" style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                        @csrf
                                        <input type="hidden" name="slug" value="{{ $item['slug'] }}">
                                        <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" max="100"
                                               style="width: 60px; text-align: center; padding: 6px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 14px;">
                                        <button type="submit" style="padding: 6px 10px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; cursor: pointer; font-size: 12px;">
                                            <i class="ph-bold ph-arrows-clockwise"></i>
                                        </button>
                                    </form>
                                </td>
                                <td style="padding: 14px 16px; text-align: right; font-size: 14px; font-weight: 600; color: var(--primary);">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="slug" value="{{ $item['slug'] }}">
                                        <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; font-size: 16px;" title="Hapus">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--bg);">
                            <td colspan="3" style="padding: 16px; font-size: 15px; font-weight: 700; text-align: right;">Total:</td>
                            <td style="padding: 16px; text-align: right; font-size: 18px; font-weight: 800; color: var(--primary);">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <a href="{{ route('toko.checkout') }}" class="btn-primary" style="padding: 14px 28px; font-size: 15px;">
                    <i class="ph-bold ph-credit-card"></i> Checkout & Bayar
                </a>
            </div>
        @endif
    </div>
</section>

{{-- ========== TENTANG KAMI ========== --}}
<section id="tentang" class="section" style="background: var(--bg);">
    <div class="container">
        <h2 class="section-title">Tentang Kelompok Tani Kami</h2>
        <p class="section-subtitle">Kami adalah sekelompok petani yang berdedikasi untuk menghasilkan beras terbaik.</p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center;">
            <div>
                <div style="font-size: 120px; text-align: center;">👨‍🌾</div>
            </div>
            <div>
                <div style="font-size: 13px; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 10px;">Cerita Kami</div>
                <h3 style="font-size: 28px; font-weight: 800; margin-bottom: 16px; line-height: 1.3;">Dari Sawah dengan Cinta, untuk Meja Makan Indonesia</h3>
                <p style="font-size: 15px; color: var(--muted); line-height: 1.8; margin-bottom: 20px;">
                    Kelompok Tani SIPADI berdiri dengan semangat gotong royong dan kecintaan terhadap tanah pertanian.
                    Kami menggabungkan kearifan lokal dengan teknologi modern untuk menghasilkan beras berkualitas tinggi
                    yang tidak hanya memenuhi kebutuhan pangan, tetapi juga mendukung kesejahteraan petani lokal.
                </p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    @foreach([
                        ['num' => '50+', 'label' => 'Petani Aktif'],
                        ['num' => '100+', 'label' => 'Hektar Sawah'],
                        ['num' => '10+', 'label' => 'Tahun Pengalaman'],
                        ['num' => '1000+', 'label' => 'Pelanggan Puas'],
                    ] as $stat)
                    <div style="background: #fff; padding: 16px; border-radius: var(--r); border: 1px solid var(--border);">
                        <div style="font-size: 24px; font-weight: 800; color: var(--primary);">{{ $stat['num'] }}</div>
                        <div style="font-size: 13px; color: var(--muted);">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function changeQty(btn, delta) {
        const input = btn.parentElement.querySelector('input[type="number"]');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > 100) val = 100;
        input.value = val;
    }
</script>
@endpush

</x-toko-layout>
