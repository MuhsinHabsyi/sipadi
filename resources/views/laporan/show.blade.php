<x-sipadi-layout title="Detail Laporan">

<div class="card">
    <div class="card-header" style="border-bottom: 1px solid var(--color-border); padding-bottom: 20px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h2 class="card-title">Laporan Operasional: Musim {{ $laporan->musim_tanam }}</h2>
            <div style="font-size: 13px; color: var(--color-muted); margin-top: 5px;">Dibuat pada: {{ \Carbon\Carbon::parse($laporan->created_at)->format('d F Y') }}</div>
        </div>
        <div>
            @if($laporan->status_finalisasi)
                <span style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; background: #E8F5EE; color: var(--color-primary); border: 1px solid #A8D5B5;"><i class="ph-bold ph-check-circle"></i> Sudah Final</span>
                <button onclick="window.print()" style="margin-left: 10px; padding: 8px 16px; background: var(--color-text); color: black; border: none; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; cursor: pointer;"><i class="ph-bold ph-printer"></i> Cetak PDF</button>
            @else
                <span style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; background: #FEF9EE; color: var(--color-warning); border: 1px solid #FAD7A1;"><i class="ph-bold ph-warning-circle"></i> Draft Laporan</span>
                
                @if(session('pengguna_role') === 'ketua')
                    <form method="POST" action="{{ route('laporan.finalisasi', $laporan) }}" style="display: inline-block; margin-left: 10px;">
                        @csrf @method('PATCH')
                        <button type="submit" style="padding: 8px 16px; background: var(--color-primary); color: black; border: none; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; cursor: pointer;">
                            <i class="ph-bold ph-check-circle"></i> Finalisasi Laporan
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <!-- Cetak Area -->
    <div id="print-area">
        <div style="text-align: center; margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 800; color: var(--color-accent);">LAPORAN OPERASIONAL KELOMPOK TANI SIPADI</h3>
            <div style="font-size: 14px; color: var(--color-muted);">Musim Tanam: {{ $laporan->musim_tanam }}</div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 30px;">
            <div style="border: 1px solid var(--color-border); padding: 16px; border-radius: var(--radius-md); text-align: center;">
                <div style="font-size: 12px; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Hasil Panen</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--color-text); margin-top: 5px;">{{ number_format($detail['total_panen'], 1, ',', '.') }} kg</div>
            </div>
            <div style="border: 1px solid var(--color-border); padding: 16px; border-radius: var(--radius-md); text-align: center;">
                <div style="font-size: 12px; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Keuntungan</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--color-primary); margin-top: 5px;">Rp {{ number_format($detail['total_keuntungan'], 0, ',', '.') }}</div>
            </div>
            <div style="border: 1px solid var(--color-border); padding: 16px; border-radius: var(--radius-md); text-align: center;">
                <div style="font-size: 12px; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Jumlah Petani</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--color-text); margin-top: 5px;">{{ $detail['jumlah_petani'] }} Orang</div>
            </div>
        </div>

        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 12px; border-bottom: 2px solid var(--color-primary); padding-bottom: 5px; display: inline-block;">Rincian Pembagian Hasil Petani</h4>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px; border: 1px solid var(--color-border);">
            <thead>
                <tr style="background: var(--color-bg);">
                    <th style="padding: 10px; text-align: center; border: 1px solid var(--color-border); font-size: 13px;">No</th>
                    <th style="padding: 10px; text-align: left; border: 1px solid var(--color-border); font-size: 13px;">Nama Petani</th>
                    <th style="padding: 10px; text-align: center; border: 1px solid var(--color-border); font-size: 13px;">Proporsi (%)</th>
                    <th style="padding: 10px; text-align: right; border: 1px solid var(--color-border); font-size: 13px;">Alokasi Diterima (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail['rincian'] as $index => $row)
                <tr>
                    <td style="padding: 10px; text-align: center; border: 1px solid var(--color-border); font-size: 14px;">{{ $index + 1 }}</td>
                    <td style="padding: 10px; text-align: left; border: 1px solid var(--color-border); font-size: 14px; font-weight: 600;">{{ $row->petani->nama_petani ?? 'Unknown' }}</td>
                    <td style="padding: 10px; text-align: center; border: 1px solid var(--color-border); font-size: 14px;">{{ number_format($row->proporsi_panen, 2, ',', '.') }}%</td>
                    <td style="padding: 10px; text-align: right; border: 1px solid var(--color-border); font-size: 14px; font-weight: 600; color: var(--color-primary);">Rp {{ number_format($row->alokasi_keuntungan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: var(--color-bg); font-weight: 700;">
                    <td colspan="2" style="padding: 10px; text-align: right; border: 1px solid var(--color-border); font-size: 14px;">TOTAL</td>
                    <td style="padding: 10px; text-align: center; border: 1px solid var(--color-border); font-size: 14px;">100%</td>
                    <td style="padding: 10px; text-align: right; border: 1px solid var(--color-border); font-size: 14px; color: var(--color-primary);">Rp {{ number_format($detail['total_keuntungan'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="display: flex; justify-content: space-between; margin-top: 60px;">
            <div style="text-align: center; width: 200px;">
                <div style="margin-bottom: 80px; font-size: 14px;">Mengetahui,<br><strong>Ketua Kelompok Tani</strong></div>
                <div style="border-bottom: 1px solid #000; margin-bottom: 5px;"></div>
                <div style="font-size: 12px; color: var(--color-muted);">Tanda Tangan & Nama Terang</div>
            </div>
            <div style="text-align: center; width: 200px;">
                <div style="margin-bottom: 80px; font-size: 14px;">Dibuat Oleh,<br><strong>Staf Keuangan</strong></div>
                <div style="border-bottom: 1px solid #000; margin-bottom: 5px;"></div>
                <div style="font-size: 12px; color: var(--color-muted);">Tanda Tangan & Nama Terang</div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        @page { margin: 20mm; }
        nav, header, aside, .sidebar, .topbar, .card-header button, .card-header form, .card-header span, a[href="{{ route('laporan.index') }}"] { 
            display: none !important; 
        }
        main, .main-content, .card { 
            margin: 0 !important; 
            padding: 0 !important; 
            box-shadow: none !important; 
            border: none !important; 
            background: #fff !important;
        }
        body { 
            background: #fff !important; 
            color: #000 !important;
        }
        #print-area { 
            display: block !important; 
            width: 100% !important; 
        }
        .card-header {
            display: none !important;
        }
    }
</style>

</x-sipadi-layout>
