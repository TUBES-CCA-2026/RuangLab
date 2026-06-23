<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\MstUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaboratoriumController extends Controller
{
    public function index(Request $request)
    {
        $query = MstLaboratorium::with('penanggungJawab');

        if ($request->filled('cari')) {
            $query->where('nama_lab', 'like', '%' . $request->cari . '%');
        }

        $labs = $query->orderBy('nama_lab')->paginate(10)->withQueryString();

        return view('admin.laboratorium.index', compact('labs'));
    }

    public function create()
    {
        $penanggungJawabs = MstUser::orderBy('nama')->get();
        return view('admin.laboratorium.create', compact('penanggungJawabs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lab' => ['required', 'string', 'max:255'],
            'penanggung_jawab' => ['required', 'exists:mst_users,id'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'fasilitas' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('laboratorium', 'public');
        }

        MstLaboratorium::create($validated);

        return redirect()->route('admin.laboratorium.index')
            ->with('success', 'Laboratorium berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lab = MstLaboratorium::findOrFail($id);
        $penanggungJawabs = MstUser::orderBy('nama')->get();
        return view('admin.laboratorium.edit', compact('lab', 'penanggungJawabs'));
    }

    public function update(Request $request, $id)
    {
        $lab = MstLaboratorium::findOrFail($id);

        $validated = $request->validate([
            'nama_lab' => ['required', 'string', 'max:255'],
            'penanggung_jawab' => ['required', 'exists:mst_users,id'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'fasilitas' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($lab->image) {
                Storage::disk('public')->delete($lab->image);
            }
            $validated['image'] = $request->file('image')->store('laboratorium', 'public');
        }

        $lab->update($validated);

        return redirect()->route('admin.laboratorium.index')
            ->with('success', 'Laboratorium berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lab = MstLaboratorium::findOrFail($id);
        $lab->delete();

        return redirect()->route('admin.laboratorium.index')
            ->with('success', 'Laboratorium berhasil dihapus.');
    }
}
