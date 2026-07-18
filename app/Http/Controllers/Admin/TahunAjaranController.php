<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstTahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = MstTahunAjaran::orderByDesc('tanggal_mulai')->paginate(10);

        return view('admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        if ($validated['is_aktif'] ?? false) {
            MstTahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        MstTahunAjaran::create($validated);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tahunAjaran = MstTahunAjaran::findOrFail($id);

        return view('admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = MstTahunAjaran::findOrFail($id);
        $validated   = $this->validasi($request);

        if ($validated['is_aktif'] ?? false) {
            MstTahunAjaran::where('id', '!=', $tahunAjaran->id)->where('is_aktif', true)->update(['is_aktif' => false]);
        }

        $tahunAjaran->update($validated);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tahunAjaran = MstTahunAjaran::findOrFail($id);
        $tahunAjaran->delete();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $validated = $request->validate([
            'tahun_ajaran'     => ['required', 'string', 'max:20'],
            'semester'         => ['required', 'in:ganjil,genap'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
            'is_aktif'         => ['nullable', 'boolean'],
        ], [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $validated['nama']     = $validated['tahun_ajaran'] . ' ' . ucfirst($validated['semester']);
        $validated['is_aktif'] = $request->boolean('is_aktif');

        return $validated;
    }
}
