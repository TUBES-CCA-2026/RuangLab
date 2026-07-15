<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        TrxReservasi::autoCompleteExpired();

        if ($request->filled('status') && $request->status === 'deleted') {
            $query = TrxReservasi::onlyTrashed()
                ->with(['user', 'detail.laboratorium']);
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

        $query = TrxReservasi::with(['user', 'detail.laboratorium'])
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
        $filename   = 'history_reservasi_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($reservasis) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No','Nama Peminjam','Laboratorium','Tanggal Pakai','Jam Mulai','Jam Selesai','Keperluan','Status','Tanggal Pengajuan','Prioritas']);
            foreach ($reservasis as $i => $r) {
                $d = $r->detail->first();
                fputcsv($handle, [
                    $i + 1,
                    $r->user->nama ?? '-',
                    $d->laboratorium->nama_lab ?? '-',
                    $d ? \Carbon\Carbon::parse($d->tanggal_pakai)->format('d/m/Y') : '-',
                    $d ? substr($d->jam_mulai, 0, 5) : '-',
                    $d ? substr($d->jam_selesai, 0, 5) : '-',
                    $r->keperluan,
                    ucwords(str_replace('_', ' ', $r->status)),
                    \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('d/m/Y'),
                    $r->is_prioritas ? 'Ya' : 'Tidak',
                ]);
            }
            fclose($handle);
        };
        

        return response()->stream($callback, 200, $headers);
    }
    public function restore($id)
{
    $reservasi = TrxReservasi::withTrashed()->findOrFail($id);
    $reservasi->restore();

    return redirect()->route('admin.history.index')
        ->with('success', 'Reservasi berhasil dikembalikan.');
}

public function forceDelete($id)
{
    $reservasi = TrxReservasi::withTrashed()->findOrFail($id);
    $reservasi->detail()->forceDelete();
    $reservasi->forceDelete();

    return redirect()->route('admin.history.index')
        ->with('success', 'Reservasi berhasil dihapus permanen.');
}
}