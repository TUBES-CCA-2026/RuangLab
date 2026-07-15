<?php

namespace App\Http\Controllers\Aslab;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        TrxReservasi::autoCompleteExpired();

        $query = TrxReservasi::with(['user', 'detail.laboratorium'])
            ->whereIn('status', ['disetujui', 'ditolak', 'sedang_dipakai', 'hangus', 'selesai']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('cari')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%');
            });
        }
        if ($request->filled('lab')) {
            $query->whereHas('detail', function ($q) use ($request) {
                $q->where('id_ruangan', $request->lab);
            });
        }
        if ($request->filled('dari')) {
            $query->whereHas('detail', function ($q) use ($request) {
                $q->where('tanggal_pakai', '>=', $request->dari);
            });
        }
        if ($request->filled('sampai')) {
            $query->whereHas('detail', function ($q) use ($request) {
                $q->where('tanggal_pakai', '<=', $request->sampai);
            });
        }

        $labs       = MstLaboratorium::orderBy('nama_lab')->get();
        $reservasis = $query->latest()->paginate(15)->withQueryString();

        return view('aslab.history.index', compact('reservasis', 'labs'));
    }
}