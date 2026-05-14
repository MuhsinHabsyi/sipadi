<x-sipadi-layout title="Tambah Pesanan Offline">

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Form Pesanan Offline</h2>
    </div>

    <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf
        <input type="hidden" name="tipe_transaksi" value="{{ $type }}">

        {{-- Tipe Pembeli --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Tipe Pembeli <span style="color: var(--color-danger);">*</span>
            </label>
            <select name="tipe_pembeli" id="select-tipe-pembeli" required
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                <option value="pelanggan" {{ old('tipe_pembeli') === 'pelanggan' ? 'selected' : '' }}>Pelanggan Biasa</option>
                <option value="distributor" {{ old('tipe_pembeli') === 'distributor' ? 'selected' : '' }}>Distributor</option>
            </select>
        </div>

        {{-- Musim Tanam --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Musim Tanam <span style="color: var(--color-danger);">*</span>
            </label>
            <select name="musim_tanam" required
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                @foreach($listMusim as $m)
                    <option value="{{ $m }}" {{ old('musim_tanam') == $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
                @if($listMusim->isEmpty())
                    <option value="MT-{{ date('Y') }}">MT-{{ date('Y') }} (Default)</option>
                @endif
            </select>
            <p style="font-size: 11px; color: var(--color-muted); margin-top: 4px;">Pilih musim tanam untuk transaksi ini agar tercatat di laporan keuangan.</p>
        </div>

        {{-- Nama Pembeli --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Nama Pembeli <span style="color: var(--color-danger);">*</span>
            </label>
            <input type="text" name="nama_pelanggan_manual" value="{{ old('nama_pelanggan_manual') }}" required
                   placeholder="Masukkan Nama Pembeli"
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
            @error('nama_pelanggan_manual') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Jumlah Pesanan --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Jumlah Pesanan (kg) <span style="color: var(--color-danger);">*</span>
            </label>
            <input type="number" step="0.1" name="jumlah_pesanan" id="input-jumlah" value="{{ old('jumlah_pesanan') }}" required
                   placeholder="Contoh: 10.5"
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
            <span style="font-size: 11px; color: var(--color-muted); margin-top: 4px; display: block;">
                Tersedia: <strong>{{ number_format($stok->stok_gudang ?? 0, 1) }}</strong> kg (stok gudang)
            </span>
            @error('jumlah_pesanan') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Total Harga --}}
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Total Harga (Rp) <span style="color: var(--color-muted); font-weight: 400;">(Otomatis)</span>
            </label>
            <div style="position: relative;">
                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--color-muted); font-size: 14px;">Rp</span>
                <input type="number" name="total_harga" id="input-total" value="{{ old('total_harga') }}" required
                       readonly style="width: 100%; padding: 10px 14px 10px 40px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 16px; font-weight: 800; color: var(--color-primary); background: #F8FAF9; cursor: not-allowed;">
            </div>
            @error('total_harga') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Tombol --}}
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('transaksi.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer;">Simpan Pesanan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputJumlah = document.getElementById('input-jumlah');
        const inputTotal = document.getElementById('input-total');
        const hargaPerKilo = {{ $stok->harga_per_kilo ?? 0 }};

        inputJumlah.addEventListener('input', function() {
            const jumlah = parseFloat(this.value) || 0;
            const total = Math.round(jumlah * hargaPerKilo);
            inputTotal.value = total;
        });
    });
</script>
@endpush

</x-sipadi-layout>
