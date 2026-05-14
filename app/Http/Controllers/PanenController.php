<?php

namespace App\Http\Controllers;

use App\Models\AnggotaPetani;
use App\Models\AlokasiBibit;
use App\Models\DataPanen;
use App\Models\StokBeras;
use Illuminate\Http\Request;

class PanenController extends Controller
{
    public function index()
    {
        $panens = DataPanen::with('petani')->latest()->get();
        return view('panen.index', compact('panens'));
    }

    public function create(Request $request)
    {
        $petanis = AnggotaPetani::all();
        $selectedPetani = null;
        $bibitRef = null;

        if ($request->has('id_petani')) {
            $selectedPetani = AnggotaPetani::find($request->id_petani);
            if ($selectedPetani) {
                $bibitRef = AlokasiBibit::where('id_petani', $selectedPetani->id)->latest()->first();
            }
        }

        return view('panen.create', compact('petanis', 'selectedPetani', 'bibitRef'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_petani' => 'required|exists:anggota_petanis,id',
            'musim_tanam' => 'required|string|max:255',
            'total_panen' => 'required|numeric|min:0.1',
        ], [
            'id_petani.required' => 'Petani wajib dipilih.',
            'musim_tanam.required' => 'Musim Tanam wajib diisi.',
            'total_panen.required' => 'Total Panen wajib diisi.',
        ]);

        // Simple anomaly check logic: if harvest is suspiciously high compared to average (e.g. > 10000)
        // For actual logic, this might depend on bibit amount.
        if ($request->total_panen > 10000 && !$request->has('confirm_anomaly')) {
            return back()->withInput()->with('error', 'Anomali data terdeteksi! Jumlah panen terlalu besar. Centang "Konfirmasi Data" jika memang benar.');
        }

        $panen = DataPanen::create([
            'id_petani' => $request->id_petani,
            'musim_tanam' => $request->musim_tanam,
            'total_panen' => $request->total_panen,
        ]);

        // Update Stok Beras (Gudang)
        $stok = StokBeras::first();
        if (!$stok) {
            $stok = StokBeras::create(['ketersediaan_stok' => 0, 'stok_ecommerce' => 0, 'stok_distributor' => 0, 'stok_gudang' => 0]);
        }
        
        $stok->stok_gudang += $request->total_panen;
        $stok->ketersediaan_stok += $request->total_panen;
        $stok->save();

        return redirect()->route('panen.index')->with('success', 'Data Panen berhasil disimpan dan stok gudang diperbarui.');
    }

    public function edit(DataPanen $panen)
    {
        $petanis = AnggotaPetani::all();
        $selectedPetani = $panen->petani;
        $bibitRef = AlokasiBibit::where('id_petani', $selectedPetani->id)->where('musim_tanam', $panen->musim_tanam)->latest()->first();
        
        return view('panen.edit', compact('panen', 'petanis', 'selectedPetani', 'bibitRef'));
    }

    public function update(Request $request, DataPanen $panen)
    {
        $request->validate([
            'id_petani' => 'required|exists:anggota_petanis,id',
            'musim_tanam' => 'required|string|max:255',
            'total_panen' => 'required|numeric|min:0.1',
        ], [
            'id_petani.required' => 'Petani wajib dipilih.',
            'musim_tanam.required' => 'Musim Tanam wajib diisi.',
            'total_panen.required' => 'Total Panen wajib diisi.',
        ]);

        if ($request->total_panen > 10000 && !$request->has('confirm_anomaly')) {
            return back()->withInput()->with('error', 'Anomali data terdeteksi! Jumlah panen terlalu besar. Centang "Konfirmasi Data" jika memang benar.');
        }

        $panen->update([
            'id_petani' => $request->id_petani,
            'musim_tanam' => $request->musim_tanam,
            'total_panen' => $request->total_panen,
        ]);

        return redirect()->route('panen.index')->with('success', 'Data Panen berhasil diperbarui.');
    }

    public function destroy(DataPanen $panen)
    {
        $panen->delete();
        return redirect()->route('panen.index')->with('success', 'Data Panen berhasil dihapus.');
    }
}
