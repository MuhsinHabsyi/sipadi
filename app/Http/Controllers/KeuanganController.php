<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        $transaksis = \App\Models\Transaksi::where('status_pesanan', \App\Models\Transaksi::STATUS_SELESAI)->get()->map(function($t) {
            return (object)[
                'tanggal' => $t->updated_at,
                'musim_tanam' => $t->musim_tanam ?? '-',
                'keterangan' => 'Pemasukan dari Transaksi #' . $t->id . ' (' . ucfirst($t->tipe_transaksi) . ')',
                'jenis_transaksi' => 'pemasukan',
                'nominal' => $t->total_harga,
            ];
        });

        $pengadaans = \App\Models\Pengadaan::where('asal_barang', 'Beli')->get()->map(function($p) {
            return (object)[
                'tanggal' => $p->created_at,
                'musim_tanam' => $p->musim_tanam ?? '-',
                'keterangan' => 'Pengadaan ' . $p->jenis_barang . ' sejumlah ' . $p->jumlah,
                'jenis_transaksi' => 'pengeluaran',
                'nominal' => $p->total_biaya,
            ];
        });

        $arusKas = $transaksis->concat($pengadaans)->sortByDesc('tanggal')->values();
        
        $pemasukan = $transaksis->sum('nominal');
        $pengeluaran = $pengadaans->sum('nominal');
        $saldo = $pemasukan - $pengeluaran;

        return view('keuangan.index', compact('arusKas', 'pemasukan', 'pengeluaran', 'saldo'));
    }

    public function create()
    {
        $musimBibit = \App\Models\AlokasiBibit::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $musimPanen = \App\Models\DataPanen::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $listMusim = $musimBibit->concat($musimPanen)->unique()->sort()->values();
        return view('keuangan.create', compact('listMusim'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'musim_tanam' => 'required|string',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'musim_tanam.required' => 'Musim tanam wajib diisi.',
            'jenis_transaksi.required' => 'Jenis transaksi wajib dipilih.',
            'nominal.required' => 'Nominal wajib diisi.',
            'nominal.min' => 'Nominal harus lebih besar dari 0.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);

        ArusKas::create([
            'tanggal' => $request->tanggal,
            'musim_tanam' => $request->musim_tanam,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('keuangan.index')->with('success', 'Data Arus Kas berhasil dicatat.');
    }
}
