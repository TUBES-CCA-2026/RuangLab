<?php

namespace App\Http\Controllers\Aslab;

use App\Http\Controllers\Controller;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    /**
     * Antrian reservasi pending (FIFO)
     */
    public function index()
    {
        $reservasis = TrxReservasi::with(['user', 'detail.laboratorium'])
            ->where('status', 'pending')
            ->oldest()
            ->paginate(10);

        return view('aslab.reservasi.index', compact('reservasis'));
    }

    /**
     * Detail reservasi
     */
    public function show($id)
    {
        $reservasi = TrxReservasi::with(['user', 'detail.laboratorium'])
            ->findOrFail($id);

        return view('aslab.reservasi.show', compact('reservasi'));
    }

    /**
     * Setujui reservasi → generate kode checkin
     */
    public function setujui(Request $request, $id)
    {
        $reservasi = TrxReservasi::findOrFail($id);

        if ($reservasi->status !== 'pending') {
            return back()->with('error', 'Reservasi ini sudah diproses.');
        }

        $reservasi->update([
            'status'       => 'disetujui',
            'batas_checkin' => now()->addMinutes(15),
        ]);

        return redirect()->route('aslab.verifikasi.index')
            ->with('success', 'Reservasi berhasil disetujui.');
    }

    /**
     * Tolak reservasi
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $reservasi = TrxReservasi::findOrFail($id);

        if ($reservasi->status !== 'pending') {
            return back()->with('error', 'Reservasi ini sudah diproses.');
        }

        $reservasi->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('aslab.verifikasi.index')
            ->with('success', 'Reservasi berhasil ditolak.');
    }
}
