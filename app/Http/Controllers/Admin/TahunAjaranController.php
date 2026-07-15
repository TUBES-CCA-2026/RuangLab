<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstTahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index(Request $request)
    {
        $query = MstTahunAjaran::query();

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('tahun_ajaran', 'like', '%' . $request->cari . '%');
            });
        }

        $tahunAjarans = $query->orderByDesc('tanggal_mulai')->paginate(10)->withQueryString();

        return view('admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        $tahunAjaran = MstTahunAjaran::create($validated);

        if ($request->boolean('is_aktif')) {
            $tahunAjaran->jadikanAktif();
        }

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

        $tahunAjaran->update($validated);

        if ($request->boolean('is_aktif')) {
            $tahunAjaran->jadikanAktif();
        }

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

    public function aktifkan($id)
    {
        $tahunAjaran = MstTahunAjaran::findOrFail($id);
        $tahunAjaran->jadikanAktif();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran "' . $tahunAjaran->nama . '" berhasil diaktifkan.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama'             => ['required', 'string', 'max:255'],
            'semester'         => ['required', 'in:ganjil,genap'],
            'tahun_ajaran'     => ['required', 'string', 'max:50'],
            'tanggal_mulai'    => ['required', 'date'],
            'tanggal_selesai'  => ['required', 'date', 'after:tanggal_mulai'],
        ], [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);
    }
}
