<?php

namespace App\Http\Controllers;

use App\Models\ArusKas;
use App\Models\DataPanen;
use App\Models\PembagianHasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BagiHasilController extends Controller
{
    public function index()
    {
        $bagiHasils = PembagianHasil::with('petani')->latest()->get();
        // Kelompokkan berdasarkan musim_tanam untuk rekap
        $rekapMusim = $bagiHasils->groupBy('musim_tanam')->map(function ($group) {
            return [
                'musim_tanam' => $group->first()->musim_tanam,
                'total_keuntungan' => $group->first()->total_keuntungan,
                'jumlah_petani' => $group->count(),
                'tanggal_dibagi' => $group->first()->created_at,
            ];
        });

        return view('bagi_hasil.index', compact('bagiHasils', 'rekapMusim'));
    }

    public function create(Request $request)
    {
        $musim_tanam = $request->query('musim_tanam');
        
        // Ambil daftar musim tanam unik dari data bibit dan panen
        $musimBibit = \App\Models\AlokasiBibit::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $musimPanen = DataPanen::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $listMusim = $musimBibit->concat($musimPanen)->unique()->sort()->values();

        $previewData = null;
        $totalKeuntungan = 0;
        $error = null;

        if ($musim_tanam) {
            // Cek apakah sudah pernah dibagi untuk musim ini
            $sudahDibagi = PembagianHasil::where('musim_tanam', $musim_tanam)->exists();
            
            if ($sudahDibagi) {
                $error = "Pembagian hasil untuk musim tanam $musim_tanam sudah dilakukan sebelumnya.";
            } else {
                // 1. Cek Kelengkapan Data Kas (Simulasi sederhana: jika ada pemasukan dan pengeluaran terkait musim ini)
                // Dalam skenario nyata, mungkin ada flag khusus di ArusKas. Kita asumsikan hitung semua kas saat ini.
                // Atau bisa dari tanggal awal musim tanam sampai akhir. Di sini kita hitung seluruh saldo positif.
                // 1. Hitung pemasukan dari Transaksi Selesai
                $pemasukan = \App\Models\Transaksi::where('musim_tanam', $musim_tanam)
                    ->where('status_pesanan', \App\Models\Transaksi::STATUS_SELESAI)
                    ->sum('total_harga');

                // 2. Hitung pengeluaran dari Pengadaan Beli
                $pengeluaran = \App\Models\Pengadaan::where('musim_tanam', $musim_tanam)
                    ->where('asal_barang', 'Beli')
                    ->sum('total_biaya');

                $totalKeuntungan = $pemasukan - $pengeluaran;

                if ($totalKeuntungan <= 0) {
                    $error = "Data kas tidak memadai atau keuntungan belum positif. Pastikan data arus kas lengkap!";
                } else {
                    // Ambil panen musim tersebut
                    $dataPanenMusim = DataPanen::with('petani')->where('musim_tanam', $musim_tanam)->get();
                    
                    if ($dataPanenMusim->isEmpty()) {
                        $error = "Tidak ada data panen untuk musim tanam $musim_tanam.";
                    } else {
                        $totalPanenSemua = $dataPanenMusim->sum('total_panen');
                        
                        // Group data panen berdasarkan petani (antisipasi jika 1 petani punya banyak lahan/record)
                        $groupedPanen = $dataPanenMusim->groupBy('id_petani');
                        
                        $previewData = [];
                        foreach ($groupedPanen as $idPetani => $records) {
                            $namaPetani = $records->first()->petani->nama_petani ?? "Petani Tidak Teridentifikasi ($idPetani)";
                            $totalPanenPetani = $records->sum('total_panen');
                            
                            $proporsi = ($totalPanenPetani / $totalPanenSemua) * 100;
                            $alokasi = ($proporsi / 100) * $totalKeuntungan;
                            
                            $previewData[] = [
                                'id_petani' => $idPetani,
                                'nama_petani' => $namaPetani,
                                'total_panen' => $totalPanenPetani,
                                'proporsi_panen' => round($proporsi, 2),
                                'alokasi_keuntungan' => round($alokasi, 2),
                            ];
                        }
                    }
                }
            }
        }

        return view('bagi_hasil.create', compact('listMusim', 'musim_tanam', 'previewData', 'totalKeuntungan', 'error'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'musim_tanam' => 'required|string',
        ]);

        $musim_tanam = $request->musim_tanam;

        if (PembagianHasil::where('musim_tanam', $musim_tanam)->exists()) {
            return back()->with('error', 'Gagal! Pembagian hasil musim ini sudah ada.');
        }

        // Recalculate on server to ensure data integrity
        $pemasukan = \App\Models\Transaksi::where('musim_tanam', $musim_tanam)
            ->where('status_pesanan', \App\Models\Transaksi::STATUS_SELESAI)
            ->sum('total_harga');

        $pengeluaran = \App\Models\Pengadaan::where('musim_tanam', $musim_tanam)
            ->where('asal_barang', 'Beli')
            ->sum('total_biaya');

        $totalKeuntungan = $pemasukan - $pengeluaran;

        if ($totalKeuntungan <= 0) {
            return back()->with('error', 'Kalkulasi gagal: Keuntungan tidak valid atau <= 0.');
        }

        $dataPanenMusim = DataPanen::where('musim_tanam', $musim_tanam)->get();
        if ($dataPanenMusim->isEmpty()) {
            return back()->with('error', 'Kalkulasi gagal: Data panen tidak ditemukan.');
        }

        $totalPanenSemua = $dataPanenMusim->sum('total_panen');
        $groupedPanen = $dataPanenMusim->groupBy('id_petani');

        try {
            DB::beginTransaction();

            $inserts = [];
            $now = now()->toDateTimeString();
            
            foreach ($groupedPanen as $idPetani => $records) {
                $totalPanenPetani = $records->sum('total_panen');
                $proporsi = ($totalPanenPetani / $totalPanenSemua) * 100;
                $alokasi = ($proporsi / 100) * $totalKeuntungan;

                $inserts[] = [
                    'id_petani' => $idPetani,
                    'musim_tanam' => $musim_tanam,
                    'proporsi_panen' => round($proporsi, 2),
                    'alokasi_keuntungan' => round($alokasi, 2),
                    'total_keuntungan' => $totalKeuntungan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            PembagianHasil::insert($inserts);

            DB::commit();
            return redirect()->route('bagi-hasil.index')->with('success', "Pembagian Hasil untuk musim $musim_tanam berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function edit(PembagianHasil $bagiHasil)
    {
        $bagiHasil->load('petani');
        return view('bagi_hasil.edit', compact('bagiHasil'));
    }

    public function update(Request $request, PembagianHasil $bagiHasil)
    {
        $request->validate([
            'alokasi_keuntungan' => 'required|numeric|min:0',
        ]);

        $bagiHasil->update([
            'alokasi_keuntungan' => $request->alokasi_keuntungan,
        ]);

        return redirect()->route('bagi-hasil.index')->with('success', 'Data pembagian hasil diperbarui.');
    }

    public function destroy(PembagianHasil $bagiHasil)
    {
        $bagiHasil->delete();
        return redirect()->route('bagi-hasil.index')->with('success', 'Catatan pembagian hasil dihapus.');
    }

    /** Hapus seluruh pembagian hasil pada satu musim tertentu */
    public function destroyByMusim(Request $request)
    {
        $musim = $request->musim_tanam;
        PembagianHasil::where('musim_tanam', $musim)->delete();
        return redirect()->route('bagi-hasil.index')->with('success', "Seluruh data pembagian hasil musim $musim telah dihapus.");
    }
}
