<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\StokBeras;
use App\Models\Pelanggan;
use App\Models\KontenLanding;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $ecommerce = Transaksi::with('pelanggan')->where('tipe_transaksi', 'ecommerce')->latest()->get();
        $offline = Transaksi::with('pelanggan')->where('tipe_transaksi', 'offline')->latest()->get();
        
        $stok = StokBeras::first();
        if (!$stok) {
            $stok = StokBeras::create(['ketersediaan_stok' => 0, 'stok_ecommerce' => 0, 'stok_distributor' => 0, 'stok_gudang' => 0]);
        }

        // Sinkronisasi Stok Gudang jika masih 0
        $totalPanen = \App\Models\DataPanen::sum('total_panen');
        if ($stok->ketersediaan_stok == 0 && $totalPanen > 0) {
            $stok->stok_gudang = $totalPanen;
            $stok->ketersediaan_stok = $totalPanen;
            $stok->save();
        }

        return view('transaksi.index', compact('ecommerce', 'offline', 'stok'));
    }

    public function updateHarga(Request $request)
    {
        $request->validate([
            'harga_per_kilo' => 'required|numeric|min:0',
        ]);

        $stok = StokBeras::first();
        if (!$stok) {
            $stok = StokBeras::create(['ketersediaan_stok' => 0, 'stok_ecommerce' => 0, 'stok_distributor' => 0, 'stok_gudang' => 0]);
        }
        $stok->update(['harga_per_kilo' => $request->harga_per_kilo]);

        return back()->with('success', 'Harga jual beras per kilo berhasil diperbarui.');
    }

    public function alokasiStok(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0.1',
        ]);

        try {
            DB::beginTransaction();
            $stok = StokBeras::first();
            
            if (!$stok || $stok->stok_gudang < $request->jumlah) {
                $max = $stok ? $stok->stok_gudang : 0;
                return back()->with('error', 'Gagal Alokasi! Stok di Gudang tidak mencukupi. Maksimal: ' . $max . ' kg.');
            }

            $stok->stok_gudang -= $request->jumlah;
            $stok->stok_ecommerce += $request->jumlah;
            $stok->save();

            DB::commit();
            return back()->with('success', 'Berhasil mengalokasikan ' . $request->jumlah . ' kg ke E-Commerce.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal alokasi stok: ' . $e->getMessage());
        }
    }

    public function konfirmasi(Transaksi $transaksi)
    {
        if ($transaksi->status_pesanan !== Transaksi::STATUS_MENUNGGU) {
            return back()->with('error', 'Pesanan sudah diproses.');
        }

        try {
            DB::beginTransaction();
            $stok = StokBeras::first();

            if ($transaksi->tipe_transaksi === 'ecommerce') {
                if ($stok->stok_ecommerce < $transaksi->jumlah_pesanan) {
                    return back()->with('error', 'Stok E-Commerce tidak mencukupi. Alokasikan lebih banyak dari gudang.');
                }
                $stok->stok_ecommerce -= $transaksi->jumlah_pesanan;
            } else { // offline
                if ($stok->stok_gudang < $transaksi->jumlah_pesanan) {
                    return back()->with('error', 'Stok Gudang tidak mencukupi untuk transaksi offline.');
                }
                $stok->stok_gudang -= $transaksi->jumlah_pesanan;
            }

            $stok->ketersediaan_stok -= $transaksi->jumlah_pesanan;
            $stok->save();

            $transaksi->update(['status_pesanan' => Transaksi::STATUS_SELESAI]);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Pesanan #' . $transaksi->id . ' berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function batalkan(Transaksi $transaksi)
    {
        if ($transaksi->status_pesanan !== Transaksi::STATUS_MENUNGGU) {
            return back()->with('error', 'Pesanan sudah diproses.');
        }
        $transaksi->update(['status_pesanan' => Transaksi::STATUS_DIBATALKAN]);
        return redirect()->route('transaksi.index')->with('success', 'Pesanan #'.$transaksi->id.' dibatalkan.');
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'offline');
        $stok = \App\Models\StokBeras::first();
        
        $musimBibit = \App\Models\AlokasiBibit::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $musimPanen = \App\Models\DataPanen::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $listMusim = $musimBibit->concat($musimPanen)->unique()->sort()->values();

        return view('transaksi.create', compact('type', 'stok', 'listMusim'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'nullable|exists:pelanggans,id',
            'nama_pelanggan_manual' => 'required_without:id_pelanggan|nullable|string|max:255',
            'tipe_pembeli' => 'nullable|in:pelanggan,distributor',
            'musim_tanam' => 'required|string',
            'jumlah_pesanan' => 'required|numeric|min:0.1',
            'tipe_transaksi' => 'required|in:ecommerce,offline',
            'total_harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $stok = StokBeras::first();
            
            if ($request->tipe_transaksi === 'ecommerce') {
                if ($stok->stok_ecommerce < $request->jumlah_pesanan) {
                    return back()->withInput()->with('error', 'Gagal! Stok E-Commerce tidak mencukupi (Tersedia: '.$stok->stok_ecommerce.' kg).');
                }
            } else { // offline
                if ($stok->stok_gudang < $request->jumlah_pesanan) {
                    return back()->withInput()->with('error', 'Gagal! Stok Gudang tidak mencukupi (Tersedia: '.$stok->stok_gudang.' kg).');
                }
            }

            // Untuk offline, tambahkan info tipe pembeli ke nama manual
            $namaManual = $request->nama_pelanggan_manual;
            if ($request->tipe_transaksi === 'offline' && $request->tipe_pembeli === 'distributor' && $namaManual) {
                $namaManual = '[Distributor] ' . $namaManual;
            }

            Transaksi::create([
                'id_pelanggan' => $request->id_pelanggan,
                'nama_pelanggan_manual' => $namaManual,
                'tanggal_pesanan' => now(),
                'musim_tanam' => $request->musim_tanam,
                'jumlah_pesanan' => $request->jumlah_pesanan,
                'harga_satuan' => $request->total_harga / $request->jumlah_pesanan,
                'total_harga' => $request->total_harga,
                'status_pesanan' => Transaksi::STATUS_MENUNGGU,
                'tipe_transaksi' => $request->tipe_transaksi,
                'jenis_beras' => 'Premium',
            ]);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function edit(Transaksi $transaksi)
    {
        $pelanggans = Pelanggan::all();
        $stok = StokBeras::first();
        $type = $transaksi->tipe_transaksi;
        
        return view('transaksi.edit', compact('transaksi', 'pelanggans', 'stok', 'type'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'id_pelanggan' => 'nullable|exists:pelanggans,id',
            'nama_pelanggan_manual' => 'required_without:id_pelanggan|nullable|string|max:255',
            'jumlah_pesanan' => 'required|numeric|min:0.1',
            'total_harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            if ($transaksi->status_pesanan === Transaksi::STATUS_SELESAI && $transaksi->jumlah_pesanan != $request->jumlah_pesanan) {
                return back()->with('error', 'Pesanan Selesai tidak dapat diubah jumlahnya.');
            }

            $transaksi->update([
                'id_pelanggan' => $request->id_pelanggan,
                'nama_pelanggan_manual' => $request->nama_pelanggan_manual,
                'jumlah_pesanan' => $request->jumlah_pesanan,
                'harga_satuan' => $request->total_harga / $request->jumlah_pesanan,
                'total_harga' => $request->total_harga,
            ]);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(Transaksi $transaksi)
    {
        try {
            DB::beginTransaction();
            
            if ($transaksi->status_pesanan === Transaksi::STATUS_SELESAI) {
                $stok = StokBeras::first();
                if ($stok) {
                    if ($transaksi->tipe_transaksi === 'ecommerce') {
                        $stok->stok_ecommerce += $transaksi->jumlah_pesanan;
                    } else {
                        $stok->stok_gudang += $transaksi->jumlah_pesanan;
                    }
                    $stok->ketersediaan_stok += $transaksi->jumlah_pesanan;
                    $stok->save();
                }
            }

            $transaksi->delete();

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /** Form edit konten landing page */
    public function editKonten()
    {
        $konten = KontenLanding::first();
        $stok = StokBeras::first();
        return view('transaksi.konten', compact('konten', 'stok'));
    }

    /** Update konten landing page */
    public function updateKonten(Request $request)
    {
        $request->validate([
            'judul_hero' => 'required|string|max:255',
            'deskripsi_hero' => 'required|string',
            'judul_toko' => 'required|string|max:255',
            'deskripsi_toko' => 'required|string',
            'produk_nama' => 'nullable|string|max:255',
            'produk_deskripsi' => 'nullable|string',
            'produk_harga' => 'nullable|numeric|min:0',
            'produk_stok_dijual' => 'nullable|integer|min:0',
            'produk_gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $konten = KontenLanding::first();
        if (!$konten) {
            $konten = new KontenLanding();
        }
        
        // Validasi stok dijual tidak melebihi stok e-commerce
        $stok = StokBeras::first();
        $stokEcommerce = $stok ? $stok->stok_ecommerce : 0;
        if ($request->filled('produk_stok_dijual') && $request->produk_stok_dijual > $stokEcommerce) {
            return back()->withInput()->with('error', 'Stok yang ditampilkan (' . $request->produk_stok_dijual . ' kg) tidak boleh melebihi stok E-Commerce saat ini (' . $stokEcommerce . ' kg). Alokasikan lebih banyak dari gudang di halaman Transaksi.');
        }

        // Handle upload gambar
        if ($request->hasFile('produk_gambar')) {
            $path = $request->file('produk_gambar')->store('produk', 'public');
            $konten->produk_gambar = $path;
        }

        $konten->fill($request->except('produk_gambar'));
        $konten->save();

        return back()->with('success', 'Katalog beras berhasil diperbarui.');
    }
}
