<?php

namespace App\Http\Controllers;

use App\Models\MstLaboratorium;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    public function index(Request $request)
    {
        $query = MstLaboratorium::where('status', true);

        if ($request->filled('cari')) {
            $query->where('nama_lab', 'like', '%' . $request->cari . '%');
        }

        $labs = $query->orderBy('nama_lab')->paginate(9)->withQueryString();

        return view('laboratorium.index', compact('labs'));
    }

    public function show($id)
    {
        $lab = MstLaboratorium::with(['penanggungJawab', 'jadwalKuliah.mataKuliah', 'jadwalKuliah.hari'])
            ->where('status', true)
            ->findOrFail($id);

        return view('laboratorium.show', compact('lab'));
    }
}
