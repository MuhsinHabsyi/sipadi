<?php

namespace App\Http\Controllers;

use App\Models\AnggotaPetani;
use Illuminate\Http\Request;

class AnggotaPetaniController extends Controller
{
    /**
     * Tampilkan daftar semua anggota petani.
     */
    public function index()
    {
        $anggotas = AnggotaPetani::latest()->get();
        return view('anggota.index', compact('anggotas'));
    }

    /**
     * Form pendaftaran anggota baru.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Simpan data anggota baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_petani'  => 'required|string|max:255',
            'umur'         => 'nullable|integer|min:1|max:150',
            'no_hp'        => 'nullable|string|max:50',
            'alamat'       => 'nullable|string',
            'nama_lahan'   => 'nullable|string|max:255',
            'alamat_lahan' => 'nullable|string',
            'luas_lahan'   => 'required|numeric|min:0.1',
            'status_lahan' => 'nullable|string|in:' . implode(',', array_keys(AnggotaPetani::STATUS_LAHAN_OPTIONS)),
        ], [
            'nama_petani.required' => 'Nama Petani wajib diisi.',
            'luas_lahan.required'  => 'Luas Lahan wajib diisi.',
            'luas_lahan.numeric'   => 'Luas Lahan harus berupa angka.',
            'umur.integer'         => 'Umur harus berupa angka.',
        ]);

        AnggotaPetani::create($request->only([
            'nama_petani', 'umur', 'no_hp', 'alamat',
            'nama_lahan', 'alamat_lahan', 'luas_lahan', 'status_lahan',
        ]));

        return redirect()->route('anggota.index')->with('success', 'Data anggota petani berhasil didaftarkan.');
    }

    /**
     * Form edit data anggota.
     */
    public function edit(AnggotaPetani $anggotum)
    {
        return view('anggota.edit', ['anggota' => $anggotum]);
    }

    /**
     * Update data anggota.
     */
    public function update(Request $request, AnggotaPetani $anggotum)
    {
        $request->validate([
            'nama_petani'  => 'required|string|max:255',
            'umur'         => 'nullable|integer|min:1|max:150',
            'no_hp'        => 'nullable|string|max:50',
            'alamat'       => 'nullable|string',
            'nama_lahan'   => 'nullable|string|max:255',
            'alamat_lahan' => 'nullable|string',
            'luas_lahan'   => 'required|numeric|min:0.1',
            'status_lahan' => 'nullable|string|in:' . implode(',', array_keys(AnggotaPetani::STATUS_LAHAN_OPTIONS)),
        ], [
            'nama_petani.required' => 'Nama Petani wajib diisi.',
            'luas_lahan.required'  => 'Luas Lahan wajib diisi.',
        ]);

        $anggotum->update($request->only([
            'nama_petani', 'umur', 'no_hp', 'alamat',
            'nama_lahan', 'alamat_lahan', 'luas_lahan', 'status_lahan',
        ]));

        return redirect()->route('anggota.index')->with('success', 'Data anggota petani berhasil diperbarui.');
    }

    /**
     * Hapus data anggota — tolak jika masih punya alokasi bibit.
     */
    public function destroy(AnggotaPetani $anggotum)
    {
        if ($anggotum->alokasiBibits()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus anggota yang masih memiliki data alokasi bibit.');
        }

        $anggotum->delete();
        return redirect()->route('anggota.index')->with('success', 'Data anggota petani berhasil dihapus.');
    }
}
