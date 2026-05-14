<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\StokBeras;
use App\Models\KontenLanding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    /** Daftar produk beras dari katalog */
    private function getProducts()
    {
        $konten = KontenLanding::first();
        $stok = StokBeras::first();

        // Jika data katalog kosong, return empty
        if (!$konten || !$konten->produk_nama) {
            return [];
        }

        $stokDijual = $konten->produk_stok_dijual ?? 0;
        $stokEcommerce = $stok ? $stok->stok_ecommerce : 0;
        $stokTersedia = min($stokDijual, $stokEcommerce);

        return [
            [
                'slug' => 'produk-utama',
                'nama' => $konten->produk_nama,
                'deskripsi' => $konten->produk_deskripsi ?? '',
                'harga' => $konten->produk_harga ?? 0,
                'gambar' => $konten->produk_gambar ? asset('storage/' . $konten->produk_gambar) : null,
                'stok_tersedia' => max(0, $stokTersedia),
            ],
        ];
    }

    /** Landing page publik */
    public function index()
    {
        $products = $this->getProducts();
        $stok = StokBeras::first();
        $konten = KontenLanding::first();
        $cart = session('cart', []);
        return view('toko.index', compact('products', 'stok', 'konten', 'cart'));
    }

    /** Tambah ke keranjang (session-based) */
    public function addToCart(Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'jumlah' => 'required|integer|min:1',
        ]);

        $products = $this->getProducts();
        $product = collect($products)->firstWhere('slug', $request->slug);

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $cart = session('cart', []);
        $slug = $request->slug;

        if (isset($cart[$slug])) {
            $cart[$slug]['jumlah'] += $request->jumlah;
        } else {
            $cart[$slug] = [
                'nama' => $product['nama'],
                'harga' => $product['harga'],
                'jumlah' => $request->jumlah,
                'slug' => $slug,
            ];
        }

        session(['cart' => $cart]);
        return back()->with('success', $product['nama'] . ' berhasil ditambahkan ke keranjang!');
    }

    /** Hapus item dari keranjang */
    public function removeFromCart(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->slug]);
        session(['cart' => $cart]);
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /** Update jumlah item */
    public function updateCart(Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'jumlah' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);
        if (isset($cart[$request->slug])) {
            $cart[$request->slug]['jumlah'] = $request->jumlah;
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /** Halaman checkout — wajib login pelanggan */
    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('toko')->with('error', 'Keranjang Anda kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['jumlah']);
        return view('toko.checkout', compact('cart', 'total'));
    }

    /** Proses checkout → simpan transaksi */
    public function processCheckout(Request $request)
    {
        $rules = [
            'nama_pelanggan_manual' => 'required|string|max:255',
            'alamat_pengantaran' => 'required|string',
        ];
        $messages = [
            'nama_pelanggan_manual.required' => 'Nama penerima wajib diisi.',
            'alamat_pengantaran.required' => 'Alamat pengantaran wajib diisi.',
        ];

        if (!$request->has('is_dummy')) {
            $rules['bukti_transfer'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            $messages['bukti_transfer.required'] = 'Bukti transfer wajib diunggah.';
            $messages['bukti_transfer.image'] = 'File harus berupa gambar.';
            $messages['bukti_transfer.max'] = 'Ukuran file maksimal 2MB.';
        }

        $request->validate($rules, $messages);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('toko')->with('error', 'Keranjang Anda kosong.');
        }

        $pelangganId = session('pelanggan_id');
        $totalJumlahKg = collect($cart)->sum('jumlah');

        try {
            DB::beginTransaction();

            // Cek stok
            $stok = StokBeras::first();
            if (!$stok || $stok->ketersediaan_stok < $totalJumlahKg) {
                return back()->with('error', 'Maaf, stok beras tidak mencukupi untuk pesanan Anda.');
            }

            // Upload bukti transfer atau gunakan dummy
            if ($request->has('is_dummy')) {
                $buktiPath = 'bukti_transfer/dummy_receipt.png';
            } else {
                $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
            }

            // Stok tidak dikurangi di sini (sesuai permintaan: dikurangi saat pesanan diterima/dikonfirmasi)
            
            // Ambil musim tanam terbaru untuk transaksi ecommerce
            $latestMusim = \App\Models\DataPanen::latest()->value('musim_tanam') ?? ('MT-' . date('Y'));

            // Buat transaksi untuk setiap item
            foreach ($cart as $item) {
                Transaksi::create([
                    'id_pelanggan' => $pelangganId,
                    'jenis_beras' => $item['nama'],
                    'musim_tanam' => $latestMusim,
                    'harga_satuan' => $item['harga'],
                    'tanggal_pesanan' => now()->toDateString(),
                    'jumlah_pesanan' => $item['jumlah'],
                    'total_harga' => $item['harga'] * $item['jumlah'],
                    'bukti_transfer' => $buktiPath,
                    'status_pesanan' => Transaksi::STATUS_MENUNGGU,
                    'tipe_transaksi' => 'ecommerce',
                    'nama_pelanggan_manual' => $request->nama_pelanggan_manual,
                    'alamat_pengantaran' => $request->alamat_pengantaran,
                ]);
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            return redirect()->route('toko.pesanan')->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi dari staf penjualan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /** Halaman riwayat pesanan pelanggan */
    public function pesanan()
    {
        $pelangganId = session('pelanggan_id');
        $transaksis = Transaksi::where('id_pelanggan', $pelangganId)->latest()->get();
        return view('toko.pesanan', compact('transaksis'));
    }
}
