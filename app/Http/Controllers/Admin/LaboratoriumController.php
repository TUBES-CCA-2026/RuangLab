<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\MstLaboratoriumImage;
use App\Models\MstRole;
use App\Models\MstUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        $penanggungJawabs = $this->calonPenanggungJawab();
        return view('admin.laboratorium.create', compact('penanggungJawabs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lab'         => ['required', 'string', 'max:255'],
            'penanggung_jawab' => [
                'required',
                Rule::exists('mst_users', 'id')->whereIn('id_role', $this->idRoleAslabLaboran()),
            ],
            'kapasitas'        => ['required', 'integer', 'min:1'],
            'fasilitas'        => ['nullable', 'string'],
            'status'           => ['required', 'boolean'],
            'image'            => ['nullable', 'image', 'max:2048'],
            'images.*'         => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('laboratorium', 'public');
        }

        $lab = MstLaboratorium::create($validated);

        // Simpan gambar tambahan
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('laboratorium', 'public');
                MstLaboratoriumImage::create([
                    'id_laboratorium' => $lab->id,
                    'image_path'      => $path,
                    'urutan'          => $index,
                ]);
            }
        }

        return redirect()->route('admin.laboratorium.index')
            ->with('success', 'Laboratorium berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lab = MstLaboratorium::with('images')->findOrFail($id);
        $penanggungJawabs = $this->calonPenanggungJawab();
        return view('admin.laboratorium.edit', compact('lab', 'penanggungJawabs'));
    }

    public function update(Request $request, $id)
    {
        $lab = MstLaboratorium::findOrFail($id);

        $validated = $request->validate([
            'nama_lab'         => ['required', 'string', 'max:255'],
            'penanggung_jawab' => [
                'required',
                Rule::exists('mst_users', 'id')->whereIn('id_role', $this->idRoleAslabLaboran()),
            ],
            'kapasitas'        => ['required', 'integer', 'min:1'],
            'fasilitas'        => ['nullable', 'string'],
            'status'           => ['required', 'boolean'],
            'image'            => ['nullable', 'image', 'max:2048'],
            'images.*'         => ['nullable', 'image', 'max:2048'],
            'hapus_images'     => ['nullable', 'array'],
            'hapus_images.*'   => ['exists:mst_laboratorium_images,id'],
        ]);

        if ($request->hasFile('image')) {
            if ($lab->image) {
                Storage::disk('public')->delete($lab->image);
            }
            $validated['image'] = $request->file('image')->store('laboratorium', 'public');
        }

        $lab->update($validated);

        // Hapus gambar yang dipilih untuk dihapus
        if ($request->filled('hapus_images')) {
            $toDelete = MstLaboratoriumImage::whereIn('id', $request->hapus_images)
                ->where('id_laboratorium', $lab->id)->get();
            foreach ($toDelete as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        // Tambah gambar baru
        if ($request->hasFile('images')) {
            $currentMax = $lab->images()->max('urutan') ?? -1;
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('laboratorium', 'public');
                MstLaboratoriumImage::create([
                    'id_laboratorium' => $lab->id,
                    'image_path'      => $path,
                    'urutan'          => $currentMax + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.laboratorium.index')
            ->with('success', 'Laboratorium berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lab = MstLaboratorium::with('images')->findOrFail($id);

        foreach ($lab->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        if ($lab->image) {
            Storage::disk('public')->delete($lab->image);
        }

        $lab->delete();

        return redirect()->route('admin.laboratorium.index')
            ->with('success', 'Laboratorium berhasil dihapus.');
    }

    /**
     * Kandidat penanggung jawab lab: hanya user dengan role aslab atau laboran.
     */
    private function calonPenanggungJawab()
    {
        return MstUser::whereIn('id_role', $this->idRoleAslabLaboran())->orderBy('nama')->get();
    }

    private function idRoleAslabLaboran(): array
    {
        return MstRole::get()
            ->filter(fn ($role) => in_array(strtolower($role->nama_role), ['aslab', 'laboran']))
            ->pluck('id')
            ->toArray();
    }
}
