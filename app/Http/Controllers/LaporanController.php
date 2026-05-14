<?php

namespace App\Http\Controllers;

use App\Models\LaporanOperasional;
use App\Models\PembagianHasil;
use App\Models\DataPanen;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = LaporanOperasional::latest()->get();
        return view('laporan.index', compact('laporans'));
    }

    public function create(Request $request)
    {
        $musim_tanam = $request->query('musim_tanam');
        // Ambil daftar musim tanam dari data bibit dan panen
        $musimBibit = \App\Models\AlokasiBibit::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $musimPanen = DataPanen::select('musim_tanam')->distinct()->pluck('musim_tanam');
        $listMusim = $musimBibit->concat($musimPanen)->unique()->sort()->values();

        $laporanDraft = null;
        if ($musim_tanam) {
            $bagiHasils = PembagianHasil::with('petani')->where('musim_tanam', $musim_tanam)->get();
            $dataPanens = DataPanen::where('musim_tanam', $musim_tanam)->get();
            
            if ($bagiHasils->isNotEmpty() && $dataPanens->isNotEmpty()) {
                $totalKeuntungan = $bagiHasils->first()->total_keuntungan;
                $totalPanen = $dataPanens->sum('total_panen');
                $jumlahPetani = $bagiHasils->count();

                $laporanDraft = [
                    'musim_tanam' => $musim_tanam,
                    'total_panen' => $totalPanen,
                    'total_keuntungan' => $totalKeuntungan,
                    'jumlah_petani' => $jumlahPetani,
                    'rincian' => $bagiHasils
                ];
            }
        }

        return view('laporan.create', compact('listMusim', 'musim_tanam', 'laporanDraft'));
    }

    public function store(Request $request)
    {
        $request->validate(['musim_tanam' => 'required|string']);

        if (LaporanOperasional::where('musim_tanam', $request->musim_tanam)->exists()) {
            return back()->with('error', 'Laporan untuk musim ini sudah dibuat.');
        }

        LaporanOperasional::create([
            'musim_tanam' => $request->musim_tanam,
            'status_finalisasi' => false,
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat sebagai draft.');
    }

    public function show(LaporanOperasional $laporan)
    {
        $bagiHasils = PembagianHasil::with('petani')->where('musim_tanam', $laporan->musim_tanam)->get();
        $dataPanens = DataPanen::where('musim_tanam', $laporan->musim_tanam)->get();
        
        $totalKeuntungan = $bagiHasils->first()->total_keuntungan ?? 0;
        $totalPanen = $dataPanens->sum('total_panen');
        $jumlahPetani = $bagiHasils->count();

        $detail = [
            'musim_tanam' => $laporan->musim_tanam,
            'total_panen' => $totalPanen,
            'total_keuntungan' => $totalKeuntungan,
            'jumlah_petani' => $jumlahPetani,
            'rincian' => $bagiHasils
        ];

        return view('laporan.show', compact('laporan', 'detail'));
    }

    public function finalisasi(LaporanOperasional $laporan)
    {
        if (session('pengguna_role') !== 'ketua') {
            return back()->with('error', 'Hanya Ketua yang dapat memfinalisasi laporan.');
        }

        $laporan->status_finalisasi = true;
        $laporan->save();

        return redirect()->route('laporan.show', $laporan)->with('success', 'Laporan berhasil difinalisasi oleh Ketua.');
    }

    public function destroy(LaporanOperasional $laporan)
    {
        $laporan->delete();
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
