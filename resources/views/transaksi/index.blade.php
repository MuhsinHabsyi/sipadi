<x-sipadi-layout title="Manajemen Transaksi">

{{-- Summary Stok Cards --}}
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
    {{-- Stok Gudang --}}
    <div class="card" style="margin: 0; padding: 20px; border-top: 4px solid #95A5A6;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 11px; font-weight: 700; color: var(--color-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Stok di Gudang</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--color-text);">{{ number_format($stok->stok_gudang ?? 0, 1, ',', '.') }} <span style="font-size: 14px; font-weight: 400;">kg</span></div>
            </div>
            <div style="width: 40px; height: 40px; background: #F8F9F9; color: #95A5A6; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="ph-bold ph-package"></i>
            </div>
        </div>
        <div style="margin-top: 12px; font-size: 12px; color: var(--color-muted);">
            Hasil panen murni yang belum dialokasikan.
        </div>
    </div>

    {{-- Stok E-Commerce --}}
    <div class="card" style="margin: 0; padding: 20px; border-top: 4px solid #3498DB;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 11px; font-weight: 700; color: var(--color-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Stok untuk E-Commerce</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--color-text);">{{ number_format($stok->stok_ecommerce ?? 0, 1, ',', '.') }} <span style="font-size: 14px; font-weight: 400;">kg</span></div>
            </div>
            <div style="width: 40px; height: 40px; background: #EBF5FB; color: #3498DB; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="ph-bold ph-shopping-bag"></i>
            </div>
        </div>
        <div style="margin-top: 12px; font-size: 12px; color: var(--color-muted);">
            Stok yang aktif dijual di landing page/toko online.
        </div>
    </div>
</div>

<div style="display: flex; flex-direction: column; gap: 24px;">
    
    {{-- Panel Alokasi Stok --}}
    <div class="card" style="margin: 0;">
        <div class="card-header">
            <h3 class="card-title"><i class="ph-bold ph-arrows-left-right"></i> Pemisahan Alokasi Stok</h3>
        </div>
        <form action="{{ route('transaksi.alokasi') }}" method="POST" style="display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: flex-end; padding: 10px 0;">
            @csrf
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Jumlah Alokasi ke E-Commerce (kg)</label>
                <input type="number" step="0.1" name="jumlah" required placeholder="Contoh: 50.5" style="width: 100%; padding: 10px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px;">
            </div>
            <button type="submit" id="btn-proses-alokasi" style="padding: 11px 24px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                Pindahkan ke E-Commerce <i class="ph-bold ph-caret-right"></i>
            </button>
        </form>
        <div style="font-size: 11px; color: var(--color-muted); margin-top: 8px;">
            Memindahkan stok dari <strong>Gudang</strong> → <strong>E-Commerce</strong>. Maksimal: <strong id="max-gudang-text">{{ number_format($stok->stok_gudang ?? 0, 1) }}</strong> kg.
        </div>
    </div>

    {{-- Panel Harga Jual --}}
    <div class="card" style="margin: 0;">
        <div class="card-header">
            <h3 class="card-title"><i class="ph-bold ph-tag"></i> Harga Jual Beras per Kilo</h3>
        </div>
        <form action="{{ route('transaksi.harga.update') }}" method="POST" style="display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: flex-end; padding: 10px 0;">
            @csrf
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Harga Jual Saat Ini (Rp/kg)</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--color-muted); font-size: 14px;">Rp</span>
                    <input type="number" name="harga_per_kilo" value="{{ (int)($stok->harga_per_kilo ?? 0) }}" required style="width: 100%; padding: 10px 10px 10px 35px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 700; color: var(--color-primary);">
                </div>
            </div>
            <button type="submit" style="padding: 11px 24px; background: #E8F5EE; color: var(--color-primary); border: 1px solid #A8D5B5; border-radius: var(--radius-md); font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                Update Harga <i class="ph-bold ph-check"></i>
            </button>
        </form>
        <div style="font-size: 11px; color: var(--color-muted); margin-top: 8px;">
            Harga ini digunakan sebagai dasar perhitungan otomatis di form tambah pesanan.
        </div>
    </div>

    {{-- Tabel E-Commerce --}}
    <div class="card" style="margin: 0;">
        <div class="card-header" style="background: #EBF5FB; border-bottom: 1px solid #D4E6F1;">
            <h2 class="card-title" style="color: #2E86C1;"><i class="ph-bold ph-globe"></i> Pesanan E-Commerce</h2>
        </div>
        @include('transaksi.partials.table', ['data' => $ecommerce])
    </div>

    {{-- Tabel Offline --}}
    <div class="card" style="margin: 0;">
        <div class="card-header" style="background: #F8F9F9; border-bottom: 1px solid #E5E7E9; display: flex; justify-content: space-between; align-items: center;">
            <h2 class="card-title" style="color: #717D7E;"><i class="ph-bold ph-storefront"></i> Pesanan Offline</h2>
            <a href="{{ route('transaksi.create', ['type' => 'offline']) }}" style="background: #717D7E; color: white; padding: 4px 10px; border-radius: 6px; text-decoration: none; font-size: 11px; font-weight: 700;">
                <i class="ph-bold ph-plus"></i> Tambah Pesanan
            </a>
        </div>
        @include('transaksi.partials.table', ['data' => $offline])
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formAlokasi = document.querySelector('form[action="{{ route('transaksi.alokasi') }}"]');
        const inputJumlah = formAlokasi.querySelector('input[name="jumlah"]');
        const maxStok = {{ $stok->stok_gudang ?? 0 }};

        formAlokasi.addEventListener('submit', function(e) {
            const jumlah = parseFloat(inputJumlah.value);
            if (jumlah > maxStok) {
                e.preventDefault();
                alert('⚠️ Stok Melebihi Batas Gudang!\n\nJumlah yang ingin dialokasikan (' + jumlah + ' kg) melebihi stok yang tersedia di gudang (' + maxStok + ' kg).');
            }
        });
    });
</script>
@endpush

</x-sipadi-layout>
