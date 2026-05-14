<x-sipadi-layout title="Edit Pesanan #{{ $transaksi->id }}">

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Edit Pesanan {{ ucfirst($type) }}</h2>
    </div>

    <form method="POST" action="{{ route('transaksi.update', $transaksi) }}">
        @csrf
        @method('PUT')

        {{-- Nama Pelanggan --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Nama Pelanggan <span style="color: var(--color-danger);">*</span>
            </label>
            @if($type === 'ecommerce')
                <select name="id_pelanggan" required
                        style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                    <option value="">— Pilih Pelanggan —</option>
                    @foreach($pelanggans as $p)
                        <option value="{{ $p->id }}" {{ old('id_pelanggan', $transaksi->id_pelanggan) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} ({{ $p->telepon }})
                        </option>
                    @endforeach
                </select>
                @error('id_pelanggan') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            @else
                <input type="text" name="nama_pelanggan_manual" value="{{ old('nama_pelanggan_manual', $transaksi->nama_pelanggan_manual) }}" required
                       placeholder="Masukkan Nama Pelanggan"
                       style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
                @error('nama_pelanggan_manual') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            @endif
        </div>

        {{-- Jumlah Pesanan --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Jumlah Pesanan (kg) <span style="color: var(--color-danger);">*</span>
            </label>
            <input type="number" step="0.1" name="jumlah_pesanan" id="input-jumlah" value="{{ old('jumlah_pesanan', $transaksi->jumlah_pesanan) }}" required
                   placeholder="Contoh: 10.5"
                   {{ $transaksi->status_pesanan === 'Selesai' ? 'readonly style=background:#F4F6F9;cursor:not-allowed;' : '' }}
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
            @if($transaksi->status_pesanan === 'Selesai')
                <span style="font-size: 11px; color: var(--color-warning); margin-top: 4px; display: block;">Jumlah tidak dapat diubah karena pesanan sudah diproses (Selesai).</span>
            @endif
            @error('jumlah_pesanan') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Total Harga --}}
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Total Harga (Rp) <span style="color: var(--color-danger);">*</span>
            </label>
            <input type="number" name="total_harga" value="{{ old('total_harga', $transaksi->total_harga) }}" required
                   placeholder="Contoh: 150000"
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; font-weight: 700; color: var(--color-primary);">
            @error('total_harga') <span style="color: var(--color-danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        {{-- Tombol --}}
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('transaksi.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer;">Update Pesanan</button>
        </div>
    </form>
</div>

</x-sipadi-layout>
