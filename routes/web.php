<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\PertanianController;
use App\Http\Controllers\CuacaController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\BagiHasilController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\AnggotaPetaniController;
use App\Http\Controllers\PengadaanController;

/*
|--------------------------------------------------------------------------
| Landing Page & Toko (Publik)
|--------------------------------------------------------------------------
*/
Route::get('/', [TokoController::class, 'index'])->name('toko');
Route::post('/cart/add', [TokoController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [TokoController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [TokoController::class, 'updateCart'])->name('cart.update');

/*
|--------------------------------------------------------------------------
| Autentikasi Pelanggan
|--------------------------------------------------------------------------
*/
Route::get('/masuk', [PelangganAuthController::class, 'showLogin'])->name('pelanggan.login');
Route::post('/masuk', [PelangganAuthController::class, 'login'])->name('pelanggan.login.post');
Route::get('/daftar', [PelangganAuthController::class, 'showRegister'])->name('pelanggan.register');
Route::post('/daftar', [PelangganAuthController::class, 'register'])->name('pelanggan.register.post');
Route::post('/pelanggan/logout', [PelangganAuthController::class, 'logout'])->name('pelanggan.logout');

/*
|--------------------------------------------------------------------------
| Area Pelanggan — wajib login pelanggan
|--------------------------------------------------------------------------
*/
Route::middleware('pelanggan.auth')->group(function () {
    Route::get('/checkout', [TokoController::class, 'checkout'])->name('toko.checkout');
    Route::post('/checkout', [TokoController::class, 'processCheckout'])->name('toko.checkout.process');
    Route::get('/pesanan-saya', [TokoController::class, 'pesanan'])->name('toko.pesanan');
});

/*
|--------------------------------------------------------------------------
| Autentikasi Internal (Staf & Ketua)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AutentikasiController::class, 'showLogin'])->name('login');
Route::post('/login', [AutentikasiController::class, 'login'])->name('autentikasi.login');
Route::post('/logout', [AutentikasiController::class, 'logout'])->name('autentikasi.logout');

/*
|--------------------------------------------------------------------------
| Rute Internal — wajib login (semua role)
|--------------------------------------------------------------------------
*/
Route::middleware('pengguna.auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $stokBeras        = \App\Models\StokBeras::value('ketersediaan_stok') ?? 0;
        $totalPetani      = \App\Models\AnggotaPetani::count();
        $totalTransaksi   = \App\Models\Transaksi::count();
        $transaksiMenunggu = \App\Models\Transaksi::where('status_pesanan', 'Menunggu')->count();
        $pemasukan        = \App\Models\ArusKas::where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $pengeluaran      = \App\Models\ArusKas::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoKas         = $pemasukan - $pengeluaran;
        $transaksiTerbaru = \App\Models\Transaksi::with('pelanggan')
                                ->latest()
                                ->take(5)
                                ->get();

        return view('dashboard', compact(
            'stokBeras', 'totalPetani', 'totalTransaksi',
            'transaksiMenunggu', 'saldoKas', 'transaksiTerbaru'
        ));
    })->name('dashboard');

    /*
    |--------------------------------------------------------------
    | Produksi — Staf Produksi & Ketua
    |--------------------------------------------------------------
    */
    Route::middleware('pengguna.auth:ketua,staf_produksi')->group(function () {
        Route::resource('pertanian', PertanianController::class);
        Route::get('pertanian/get-petani/{id}', [PertanianController::class, 'getDataPetani'])
             ->name('pertanian.getPetani');
        Route::resource('panen', PanenController::class);
        Route::get('cuaca', [CuacaController::class, 'index'])->name('cuaca.index');
        Route::resource('pengadaan', PengadaanController::class)->except(['show', 'edit', 'update']);
    });

    /*
    |--------------------------------------------------------------
    | Penjualan — Staf Penjualan & Ketua
    |--------------------------------------------------------------
    */
    Route::middleware('pengguna.auth:ketua,staf_penjualan')->group(function () {
        Route::resource('transaksi', TransaksiController::class);
        Route::post('/transaksi/alokasi', [TransaksiController::class, 'alokasiStok'])->name('transaksi.alokasi');
        Route::post('/transaksi/update-harga', [TransaksiController::class, 'updateHarga'])->name('transaksi.harga.update');
        Route::patch('/transaksi/{transaksi}/konfirmasi', [TransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
        Route::patch('transaksi/{transaksi}/batalkan', [TransaksiController::class, 'batalkan'])
             ->name('transaksi.batalkan');
        
        // Edit Landing Page
        Route::get('konten-landing', [TransaksiController::class, 'editKonten'])
             ->name('transaksi.konten.edit');
        Route::patch('konten-landing', [TransaksiController::class, 'updateKonten'])
             ->name('transaksi.konten.update');
    });

    /*
    |--------------------------------------------------------------
    | Keuangan — Staf Keuangan & Ketua
    |--------------------------------------------------------------
    */
    Route::middleware('pengguna.auth:ketua,staf_keuangan')->group(function () {
        Route::resource('keuangan', KeuanganController::class);
        Route::resource('bagi-hasil', BagiHasilController::class);
        Route::delete('bagi-hasil-musim', [BagiHasilController::class, 'destroyByMusim'])
             ->name('bagi-hasil.destroyMusim');
        Route::resource('laporan', LaporanController::class);
        Route::patch('laporan/{laporan}/finalisasi', [LaporanController::class, 'finalisasi'])
             ->name('laporan.finalisasi');
    });

    /*
    |--------------------------------------------------------------
    | Administrasi — Ketua saja
    |--------------------------------------------------------------
    */
    Route::middleware('pengguna.auth:ketua')->group(function () {
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('anggota', AnggotaPetaniController::class);
    });
});
