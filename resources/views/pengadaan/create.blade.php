<x-sipadi-layout title="Tambah Pengadaan">

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">Form Tambah Pengadaan</h2>
    </div>

    <form method="POST" action="{{ route('pengadaan.store') }}">
        @csrf

        {{-- Jenis Barang --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Jenis Barang <span style="color: var(--color-danger);">*</span>
            </label>
            <select name="jenis_barang" required
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                <option value="Bibit" {{ old('jenis_barang') == 'Bibit' ? 'selected' : '' }}>Bibit</option>
                <option value="Pupuk" {{ old('jenis_barang') == 'Pupuk' ? 'selected' : '' }}>Pupuk</option>
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
            <p style="font-size: 11px; color: var(--color-muted); margin-top: 4px;">Pilih musim tanam untuk pengadaan ini agar tercatat di laporan keuangan.</p>
        </div>

        {{-- Asal Barang --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Asal Barang <span style="color: var(--color-danger);">*</span>
            </label>
            <select name="asal_barang" id="select-asal" required
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px; background: white;">
                <option value="Beli" {{ old('asal_barang') == 'Beli' ? 'selected' : '' }}>Beli (Menggunakan Dana Kas)</option>
                <option value="Dinas" {{ old('asal_barang') == 'Dinas' ? 'selected' : '' }}>Dari Dinas (Gratis)</option>
            </select>
            <span style="font-size: 11px; color: var(--color-muted); margin-top: 4px; display: block;">Pembelian akan otomatis dicatat sebagai pengeluaran di menu Keuangan.</span>
        </div>

        {{-- Jumlah --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Jumlah <span style="color: var(--color-danger);">*</span>
            </label>
            <input type="number" name="jumlah" id="input-jumlah" value="{{ old('jumlah') }}" required min="1"
                   placeholder="Contoh: 50"
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
        </div>

        {{-- Harga Satuan --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Harga Satuan (Rp) <span style="color: var(--color-danger);">*</span>
            </label>
            <input type="number" name="harga" id="input-harga" value="{{ old('harga') }}" required min="0"
                   placeholder="Contoh: 150000"
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
        </div>

        {{-- Total Harga --}}
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Total Biaya (Rp)
            </label>
            <input type="number" id="input-total" readonly
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 16px; font-weight: 800; color: #CB4335; background: #F8FAF9; cursor: not-allowed;">
        </div>

        {{-- Keadaan --}}
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                Keadaan (Opsional)
            </label>
            <input type="text" name="keadaan" value="{{ old('keadaan') }}"
                   placeholder="Contoh: Baik, Kondisi Karung Robek, dll."
                   style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-md); font-family: inherit; font-size: 14px;">
        </div>

        {{-- Tombol --}}
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('pengadaan.index') }}" style="padding: 10px 16px; background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 14px; font-weight: 600; text-decoration: none;">Batal</a>
            <button type="submit" style="padding: 10px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; cursor: pointer;">Simpan Pengadaan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAsal = document.getElementById('select-asal');
        const inputHarga = document.getElementById('input-harga');
        const inputJumlah = document.getElementById('input-jumlah');
        const inputTotal = document.getElementById('input-total');

        function calculate() {
            if (selectAsal.value === 'Dinas') {
                inputHarga.value = 0;
                inputHarga.readOnly = true;
                inputHarga.style.backgroundColor = '#F8FAF9';
            } else {
                inputHarga.readOnly = false;
                inputHarga.style.backgroundColor = 'white';
            }

            const jumlah = parseFloat(inputJumlah.value) || 0;
            const harga = parseFloat(inputHarga.value) || 0;
            inputTotal.value = jumlah * harga;
        }

        selectAsal.addEventListener('change', calculate);
        inputHarga.addEventListener('input', calculate);
        inputJumlah.addEventListener('input', calculate);

        calculate(); // initial run
    });
</script>
@endpush

</x-sipadi-layout>
