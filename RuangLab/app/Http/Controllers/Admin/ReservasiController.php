<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $query = TrxReservasi::with(['user', 'detail.laboratorium']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cari')) {
            $query->where('kode_reservasi', 'like', '%' . $request->cari . '%');
        }

        $reservasis = $query->latest()->paginate(10)->withQueryString();

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function show($id)
    {
        $reservasi = TrxReservasi::with(['user', 'detail.laboratorium'])->findOrFail($id);
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:disetujui,ditolak,sedang_dipakai,hangus'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $reservasi = TrxReservasi::findOrFail($id);
        $reservasi->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.reservasi.show', $reservasi->id)
            ->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
