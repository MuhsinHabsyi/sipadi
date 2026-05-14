<?php

namespace App\Http\Controllers;

use App\Models\AnggotaPetani;
use App\Models\AlokasiBibit;
use Illuminate\Http\Request;

class PertanianController extends Controller
{
    public function index()
    {
        $alokasis = AlokasiBibit::with('petani')->latest()->get();
        return view('pertanian.index', compact('alokasis'));
    }

    public function create()
    {
        $petanis = AnggotaPetani::orderBy('nama_petani')->get();
        return view('pertanian.create', compact('petanis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_petani'    => 'required|exists:anggota_petanis,id',
            'musim_tanam'  => 'required|string|max:255',
            'jumlah_bibit' => 'required|numeric|min:0.01',
        ], [
            'id_petani.required'    => 'Pilih salah satu Nama Petani.',
            'musim_tanam.required'  => 'Musim Tanam wajib diisi.',
            'jumlah_bibit.required' => 'Jumlah Bibit wajib diisi.',
        ]);

        AlokasiBibit::create($request->only(['id_petani', 'musim_tanam', 'jumlah_bibit']));

        return redirect()->route('pertanian.index')->with('success', 'Alokasi Bibit berhasil ditambahkan.');
    }

    public function edit(AlokasiBibit $pertanian)
    {
        $petanis = AnggotaPetani::orderBy('nama_petani')->get();
        return view('pertanian.edit', compact('pertanian', 'petanis'));
    }

    public function update(Request $request, AlokasiBibit $pertanian)
    {
        $request->validate([
            'id_petani'    => 'required|exists:anggota_petanis,id',
            'musim_tanam'  => 'required|string|max:255',
            'jumlah_bibit' => 'required|numeric|min:0.01',
        ], [
            'id_petani.required'    => 'Pilih salah satu Nama Petani.',
            'musim_tanam.required'  => 'Musim Tanam wajib diisi.',
            'jumlah_bibit.required' => 'Jumlah Bibit wajib diisi.',
        ]);

        $pertanian->update($request->only(['id_petani', 'musim_tanam', 'jumlah_bibit']));

        return redirect()->route('pertanian.index')->with('success', 'Alokasi Bibit berhasil diperbarui.');
    }

    public function destroy(AlokasiBibit $pertanian)
    {
        $pertanian->delete();
        return redirect()->route('pertanian.index')->with('success', 'Alokasi Bibit berhasil dihapus.');
    }

    /**
     * API endpoint — mengembalikan data petani (luas_lahan) untuk auto-populate.
     */
    public function getDataPetani($id)
    {
        $petani = AnggotaPetani::findOrFail($id);

        return response()->json([
            'id'         => $petani->id,
            'nama_petani'=> $petani->nama_petani,
            'luas_lahan' => $petani->luas_lahan,
        ]);
    }
}
