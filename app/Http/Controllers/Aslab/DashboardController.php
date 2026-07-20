<?php

namespace App\Http\Controllers\Aslab;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\TrxDetailReservasi;
use App\Models\TrxReservasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'reservasi_saya'  => TrxReservasi::where('id_user', $user->id)->tahunAjaranAktif()->count(),
            'reservasi_aktif' => TrxReservasi::where('id_user', $user->id)->tahunAjaranAktif()
                                    ->whereIn('status', ['disetujui', 'sedang_dipakai'])->count(),
            'total_lab'       => MstLaboratorium::where('status', true)->count(),
            'lab_dipakai'     => TrxDetailReservasi::whereDate('tanggal_pakai', today())
                                    ->whereHas('reservasi', fn($q) =>
                                        $q->tahunAjaranAktif()->whereIn('status', ['disetujui', 'sedang_dipakai'])
                                    )->distinct('id_ruangan')->count('id_ruangan'),
        ];

        // 5 reservasi terakhir milik aslab ini
        $reservasiSaya = TrxReservasi::where('id_user', $user->id)
            ->tahunAjaranAktif()
            ->with('detail.laboratorium')
            ->latest()
            ->take(5)
            ->get();

        // Semua lab aktif + jadwal yang sedang/akan dipakai hari ini
        $labs = MstLaboratorium::where('status', true)
            ->with(['detailReservasi' => function ($q) {
                $q->whereDate('tanggal_pakai', today())
                  ->whereHas('reservasi', fn($rq) =>
                      $rq->tahunAjaranAktif()->whereIn('status', ['disetujui', 'sedang_dipakai'])
                  )
                  ->with('reservasi.user')
                  ->orderBy('jam_mulai');
            }])
            ->orderBy('nama_lab')
            ->get();

        // Semua jadwal 7 hari ke depan (untuk kalender mingguan)
        $jadwalMendatang = TrxDetailReservasi::with(['reservasi.user', 'laboratorium'])
            ->whereHas('reservasi', fn($q) =>
                $q->tahunAjaranAktif()->whereIn('status', ['disetujui', 'sedang_dipakai'])
            )
            ->whereBetween('tanggal_pakai', [today(), today()->addDays(6)])
            ->orderBy('tanggal_pakai')
            ->orderBy('jam_mulai')
            ->get();

        return view('aslab.dashboard', compact(
            'stats', 'reservasiSaya', 'labs', 'jadwalMendatang'
        ));
    }
}
