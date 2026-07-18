<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstMataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MstMataKuliah::query();

        if ($request->filled('cari')) {
            $query->where('nama_matkul', 'like', '%' . $request->cari . '%');
        }

        $matkuls = $query->orderBy('nama_matkul')->paginate(10)->withQueryString();

        return view('admin.mata-kuliah.index', compact('matkuls'));
    }

    public function create()
    {
        return view('admin.mata-kuliah.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        MstMataKuliah::create($validated);

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $matkul = MstMataKuliah::findOrFail($id);

        return view('admin.mata-kuliah.edit', compact('matkul'));
    }

    public function update(Request $request, $id)
    {
        $matkul    = MstMataKuliah::findOrFail($id);
        $validated = $this->validasi($request);

        $matkul->update($validated);

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $matkul = MstMataKuliah::findOrFail($id);
        $matkul->delete();

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $validated = $request->validate([
            'nama_matkul'   => ['required', 'string', 'max:255'],
            'nama_dosen'    => ['required', 'string', 'max:255'],
            'sks'           => ['required', 'integer', 'min:1', 'max:10'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $validated['frekuensi'] = 1;

        return $validated;
    }
}
