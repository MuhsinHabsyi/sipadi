<x-toko-layout title="Checkout">

<section style="min-height: calc(100vh - 72px); padding: 60px 40px; background: var(--bg);">
    <div class="container" style="max-width: 800px;">

        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 28px; font-weight: 800;">Checkout Pesanan</h1>
            <p style="font-size: 14px; color: var(--muted); margin-top: 6px;">Pastikan pesanan Anda sudah benar, lalu unggah bukti transfer.</p>
        </div>

        {{-- Ringkasan Pesanan --}}
        <div style="background: #fff; border-radius: var(--rl); border: 1px solid var(--border); overflow: hidden; margin-bottom: 28px;">
            <div style="padding: 18px 20px; font-size: 15px; font-weight: 700; border-bottom: 1px solid var(--border); background: var(--bg);">
                <i class="ph-bold ph-receipt" style="color: var(--primary);"></i> Ringkasan Pesanan
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; color: var(--muted);">Produk</th>
                        <th style="padding: 12px 16px; text-align: center; font-size: 13px; color: var(--muted);">Harga/kg</th>
                        <th style="padding: 12px 16px; text-align: center; font-size: 13px; color: var(--muted);">Jumlah</th>
                        <th style="padding: 12px 16px; text-align: right; font-size: 13px; color: var(--muted);">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 16px; font-size: 14px; font-weight: 600;">{{ $item['nama'] }}</td>
                        <td style="padding: 12px 16px; text-align: center; font-size: 14px;">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td style="padding: 12px 16px; text-align: center; font-size: 14px;">{{ $item['jumlah'] }} kg</td>
                        <td style="padding: 12px 16px; text-align: right; font-size: 14px; font-weight: 600;">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #E8F5EE;">
                        <td colspan="3" style="padding: 14px 16px; font-size: 16px; font-weight: 700; text-align: right;">Total Pembayaran:</td>
                        <td style="padding: 14px 16px; text-align: right; font-size: 20px; font-weight: 800; color: var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Info Transfer --}}
        <div style="background: #fff; border-radius: var(--rl); border: 1px solid var(--border); padding: 24px; margin-bottom: 28px;">
            <div style="font-size: 15px; font-weight: 700; margin-bottom: 16px;">
                <i class="ph-bold ph-bank" style="color: var(--primary);"></i> Informasi Rekening Transfer
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="padding: 16px; background: var(--bg); border-radius: var(--r); border: 1px solid var(--border);">
                    <div style="font-size: 12px; color: var(--muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Bank BRI</div>
                    <div style="font-size: 18px; font-weight: 700; font-family: monospace;">1234-5678-9012-3456</div>
                    <div style="font-size: 13px; color: var(--muted); margin-top: 4px;">a.n. Kelompok Tani SIPADI</div>
                </div>
                <div style="padding: 16px; background: var(--bg); border-radius: var(--r); border: 1px solid var(--border);">
                    <div style="font-size: 12px; color: var(--muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Bank BCA</div>
                    <div style="font-size: 18px; font-weight: 700; font-family: monospace;">9876-5432-1098-7654</div>
                    <div style="font-size: 13px; color: var(--muted); margin-top: 4px;">a.n. Kelompok Tani SIPADI</div>
                </div>
            </div>
        </div>

        {{-- Upload Bukti Transfer --}}
        <div style="background: #fff; border-radius: var(--rl); border: 1px solid var(--border); padding: 24px;">
            <div style="font-size: 15px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="ph-bold ph-upload-simple" style="color: var(--primary);"></i> Unggah Bukti Transfer</span>
                <span style="font-size: 11.5px; font-weight: 600; color: var(--color-muted); background: var(--bg); padding: 4px 10px; border-radius: 12px;">Mode Simulasi Tersedia</span>
            </div>

            @if($errors->any())
                <div style="background: #FEF2F2; color: #991B1B; border: 1px formatting solid #FCA5A5; padding: 12px; border-radius: 10px; font-size: 13px; margin-bottom: 16px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('toko.checkout.process') }}" enctype="multipart/form-data" id="checkout-form">
                @csrf
                <input type="hidden" name="is_dummy" id="is-dummy-input" value="" disabled>

                {{-- Info Pengiriman --}}
                <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
                    <div style="font-size: 14px; font-weight: 700; margin-bottom: 12px; color: var(--text);">
                        <i class="ph-bold ph-truck" style="color: var(--primary);"></i> Informasi Pengiriman
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Penerima <span style="color: red;">*</span></label>
                        <input type="text" name="nama_pelanggan_manual" required placeholder="Masukkan nama penerima beras" style="width: 100%; padding: 12px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Alamat Lengkap Pengantaran <span style="color: red;">*</span></label>
                        <textarea name="alamat_pengantaran" required rows="3" placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Desa, Kecamatan)" style="width: 100%; padding: 12px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 14px; resize: vertical;"></textarea>
                    </div>
                </div>

                <div style="border: 2px dashed var(--border); border-radius: var(--r); padding: 40px; text-align: center; margin-bottom: 20px; transition: all .2s; cursor: pointer;" id="drop-area"
                     onclick="document.getElementById('bukti-input').click()">
                    <i class="ph-bold ph-image" style="font-size: 40px; color: var(--muted); display: block; margin-bottom: 10px;"></i>
                    <div style="font-size: 14px; font-weight: 600; margin-bottom: 4px;" id="file-label">Klik untuk memilih foto bukti transfer asli</div>
                    <div style="font-size: 12px; color: var(--muted);">Format: JPG, JPEG, PNG (Maks. 2MB)</div>
                    <input type="file" name="bukti_transfer" id="bukti-input" accept="image/jpg,image/jpeg,image/png" required
                           style="display: none;" onchange="previewFile(this)">
                </div>
                <div id="preview-img" style="display: none; text-align: center; margin-bottom: 20px;">
                    <img id="preview-src" src="" alt="Preview" style="max-height: 200px; border-radius: var(--r); border: 1px solid var(--border);">
                </div>

                {{-- Opsi Cepat Dummy Upload --}}
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 16px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: #1E293B;">Tidak punya file gambar saat ini?</div>
                        <div style="font-size: 12px; color: #64748B; margin-top: 2px;">Gunakan bukti transfer simulasi (dummy) untuk langsung memproses pesanan pengujian.</div>
                    </div>
                    <button type="button" onclick="useDummyReceipt()" class="btn-outline" style="padding: 10px 16px; font-size: 13px; font-weight: 700; border-color: #CBD5E1; color: #334155; flex-shrink: 0; background: #fff;">
                        <i class="ph-bold ph-lightning"></i> Gunakan Dummy Bukti
                    </button>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('toko') }}#keranjang" class="btn-outline" style="padding: 12px 20px;">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn-primary" style="padding: 12px 28px; font-size: 15px;">
                        <i class="ph-bold ph-check-circle"></i> Konfirmasi & Kirim Pesanan
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

@push('scripts')
<script>
    function previewFile(input) {
        const label = document.getElementById('file-label');
        const preview = document.getElementById('preview-img');
        const img = document.getElementById('preview-src');
        const dummyInput = document.getElementById('is-dummy-input');
        
        if (input.files && input.files[0]) {
            dummyInput.disabled = true;
            label.textContent = input.files[0].name;
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function useDummyReceipt() {
        const fileInput = document.getElementById('bukti-input');
        const dummyInput = document.getElementById('is-dummy-input');
        const label = document.getElementById('file-label');
        const preview = document.getElementById('preview-img');
        const img = document.getElementById('preview-src');

        // Nonaktifkan attribute required dari file input agar form bisa disubmit
        fileInput.required = false;
        dummyInput.disabled = false;
        dummyInput.value = "1";

        label.textContent = "✓ Menggunakan Bukti Transfer Dummy (Simulasi)";
        label.style.color = "var(--color-primary)";
        
        // Buat canvas gambar dummy sederhana di sisi klien untuk visual feedback
        const canvas = document.createElement('canvas');
        canvas.width = 400; canvas.height = 200;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#E8F5EE'; ctx.fillRect(0, 0, 400, 200);
        ctx.fillStyle = '#1B4332'; ctx.font = 'bold 18px sans-serif'; ctx.textAlign = 'center';
        ctx.fillText('BUKTI TRANSFER DUMMY', 200, 90);
        ctx.fillStyle = '#2D6A4F'; ctx.font = '14px sans-serif';
        ctx.fillText('Simulasi Pembayaran SIPADI', 200, 120);
        
        img.src = canvas.toDataURL();
        preview.style.display = 'block';
    }
</script>
@endpush

</x-toko-layout>
