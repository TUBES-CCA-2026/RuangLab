<?php

namespace App\Http\Controllers\Admin;

use App\Exports\HistoryReservasiExport;
use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        TrxReservasi::autoCompleteExpired();

        if ($request->filled('status') && $request->status === 'deleted') {
            $query = TrxReservasi::onlyTrashed()
                ->with([
                    'user',
                    'detail' => fn ($q) => $q->withTrashed()->with('laboratorium'),
                ]);
        } else {
            $query = TrxReservasi::with(['user', 'detail.laboratorium'])
                ->whereIn('status', ['disetujui', 'ditolak', 'sedang_dipakai', 'hangus', 'selesai']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
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

        return view('admin.history.index', compact('reservasis', 'labs'));
    }

    public function export(Request $request)
    {
        TrxReservasi::autoCompleteExpired();

        $query = TrxReservasi::with(['user', 'detail.laboratorium', 'detail.mataKuliah'])
            ->whereIn('status', ['disetujui', 'ditolak', 'sedang_dipakai', 'hangus', 'selesai']);

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('cari')) {
            $query->whereHas('user', fn($q) => $q->where('nama', 'like', '%'.$request->cari.'%'));
        }
        if ($request->filled('lab')) {
            $query->whereHas('detail', fn($q) => $q->where('id_ruangan', $request->lab));
        }
        if ($request->filled('dari')) {
            $query->whereHas('detail', fn($q) => $q->where('tanggal_pakai', '>=', $request->dari));
        }
        if ($request->filled('sampai')) {
            $query->whereHas('detail', fn($q) => $q->where('tanggal_pakai', '<=', $request->sampai));
        }

        $reservasis = $query->latest()->get();
        $filename   = 'history_reservasi_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new HistoryReservasiExport($reservasis), $filename);
    }
    public function restore($id)
{
    $reservasi = TrxReservasi::withTrashed()->findOrFail($id);
    $reservasi->restore();
    $reservasi->detail()->onlyTrashed()->restore();

    // back() supaya tetap di halaman History Reservasi dengan filter/tab
    // (mis. "Dihapus (Soft Delete)") yang sedang dilihat, bukan direset ke
    // tampilan default.
    return back()->with('success', 'Reservasi berhasil dikembalikan.');
}

public function forceDelete($id)
{
    $reservasi = TrxReservasi::withTrashed()->findOrFail($id);
    $reservasi->detail()->forceDelete();
    $reservasi->forceDelete();

    return back()->with('success', 'Reservasi berhasil dihapus permanen.');
}
}