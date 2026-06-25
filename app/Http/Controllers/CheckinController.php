<?php

namespace App\Http\Controllers;

use App\Models\TrxReservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CheckinController
 *
 * Alur fitur QR (final):
 *  1. Laboran/admin membuka detail reservasi yang berstatus "disetujui".
 *     Di halaman itu tampil QR code (lihat admin/reservasi/show.blade.php).
 *     QR berisi URL: route('reservasi.checkin', ['kode' => kode_checkin]).
 *  2. Peminjam membuka detail reservasinya di HP, menekan "Scan QR Check-in",
 *     kamera terbuka (lihat reservasi/show.blade.php), lalu mengarahkan ke
 *     QR di layar laboran.
 *  3. Browser peminjam (sudah login) membuka URL hasil scan -> masuk ke
 *     method scan() di bawah. Diverifikasi: reservasi dengan kode_checkin itu
 *     milik peminjam yang login + statusnya "disetujui" -> diubah jadi
 *     "sedang_dipakai" dan checked_in_at diisi.
 *
 * Catatan penyesuaian:
 *  - Model: App\Models\TrxReservasi (sesuai project).
 *  - Kolom check-in (checked_in_at) ditambahkan lewat migration terlampir.
 *  - Kepemilikan reservasi dicek lewat relasi $reservasi->user (tanpa menebak
 *    nama kolom foreign key), membandingkan primary key user dengan Auth::id().
 */
class CheckinController extends Controller
{
    /**
     * Tujuan QR. Dipanggil saat peminjam berhasil scan QR laboran.
     * GET /reservasi/checkin/{kode}
     */
    public function scan(Request $request, string $kode)
    {
        // Harus login.
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login dulu, lalu scan ulang QR.');
        }

        $reservasi = TrxReservasi::where('kode_checkin', $kode)->first();

        if (!$reservasi) {
            return view('reservasi.checkin-result', [
                'ok'      => false,
                'message' => 'QR tidak valid atau reservasi tidak ditemukan.',
            ]);
        }

        // Pastikan reservasi ini milik peminjam yang login.
        $pemilikId = optional($reservasi->user)->getKey();
        if ((string) $pemilikId !== (string) Auth::id()) {
            return view('reservasi.checkin-result', [
                'ok'      => false,
                'message' => 'QR ini bukan untuk reservasi kamu.',
            ]);
        }

        // Hanya reservasi yang sudah disetujui yang boleh check-in.
        if ($reservasi->status !== 'disetujui') {

            // Kalau sudah pernah check-in (sedang_dipakai), anggap sukses (idempoten).
            if ($reservasi->status === 'sedang_dipakai') {
                return view('reservasi.checkin-result', [
                    'ok'        => true,
                    'message'   => 'Kamu sudah check-in sebelumnya.',
                    'reservasi' => $reservasi,
                ]);
            }

            return view('reservasi.checkin-result', [
                'ok'        => false,
                'message'   => 'Reservasi belum bisa check-in (status: '
                                . str_replace('_', ' ', $reservasi->status) . ').',
                'reservasi' => $reservasi,
            ]);
        }

        // Catat check-in.
        $reservasi->status        = 'sedang_dipakai';
        $reservasi->checked_in_at = now();
        $reservasi->save();

        return view('reservasi.checkin-result', [
            'ok'        => true,
            'message'   => 'Check-in berhasil. Selamat menggunakan ruangan!',
            'reservasi' => $reservasi,
        ]);
    }
}
