<?php

namespace App\Http\Controllers\Aslab;

use App\Notifications\ReservasiStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\TrxDetailReservasi;
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

        $detail = $reservasi->detail()->first();

        if ($detail) {
            $bentrok = TrxDetailReservasi::where('id_ruangan', $detail->id_ruangan)
                ->where('tanggal_pakai', $detail->tanggal_pakai)
                ->where('id_reservasi', '!=', $reservasi->id)
                ->whereHas('reservasi', function ($q) {
                    $q->whereIn('status', ['disetujui', 'sedang_dipakai']);
                })
                ->where(function ($q) use ($detail) {
                    $q->whereBetween('jam_mulai', [$detail->jam_mulai, $detail->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$detail->jam_mulai, $detail->jam_selesai])
                      ->orWhere(function ($q2) use ($detail) {
                          $q2->where('jam_mulai', '<=', $detail->jam_mulai)
                             ->where('jam_selesai', '>=', $detail->jam_selesai);
                      });
                })->exists();

            if ($bentrok) {
                return back()->with('error', 'Tidak bisa menyetujui: laboratorium sudah dibooking pihak lain pada waktu yang sama. Tolak salah satu reservasi terlebih dahulu.');
            }
        }

        $reservasi->update([
            'status'       => 'disetujui',
            'batas_checkin' => now()->addMinutes(15),
        ]);
        // Kirim notifikasi ke peminjam
        try {
            $reservasi->load('user', 'detail.laboratorium');
            $reservasi->user->notify(new ReservasiStatusChanged($reservasi));
        } catch (\Exception $e) {}

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
        // Kirim notifikasi ke peminjam
        try {
            $reservasi->load('user', 'detail.laboratorium');
            $reservasi->user->notify(new ReservasiStatusChanged($reservasi));
        } catch (\Exception $e) {}
        return redirect()->route('aslab.verifikasi.index')
            ->with('success', 'Reservasi berhasil ditolak.');
    }
}
