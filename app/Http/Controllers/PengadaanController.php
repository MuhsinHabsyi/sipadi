<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index()
    {
        $pengadaans = Pengadaan::latest()->get();
        return view('pengadaan.index', compact('pengadaans'));
    }

    public function create()
    {
        // Gabungkan musim tanam dari AlokasiBibit dan DataPanen agar lebih lengkap
        $musimBibit = \App\Models\AlokasiBibit::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $musimPanen = \App\Models\DataPanen::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $listMusim = $musimBibit->concat($musimPanen)->unique()->sort()->values();
        
        return view('pengadaan.create', compact('listMusim'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_barang' => 'required|in:Bibit,Pupuk',
            'musim_tanam' => 'required|string',
            'asal_barang' => 'required|in:Beli,Dinas',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'keadaan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $harga = $request->asal_barang === 'Dinas' ? 0 : $request->harga;
            $totalBiaya = $harga * $request->jumlah;

            $pengadaan = Pengadaan::create([
                'jenis_barang' => $request->jenis_barang,
                'musim_tanam' => $request->musim_tanam,
                'asal_barang' => $request->asal_barang,
                'jumlah' => $request->jumlah,
                'harga' => $harga,
                'total_biaya' => $totalBiaya,
                'keadaan' => $request->keadaan,
            ]);

            DB::commit();
            return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan pengadaan: ' . $e->getMessage());
        }
    }

    public function destroy(Pengadaan $pengadaan)
    {
        $pengadaan->delete();
        return redirect()->route('pengadaan.index')->with('success', 'Data pengadaan dihapus.');
    }
}
