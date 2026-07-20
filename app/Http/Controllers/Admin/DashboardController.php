<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\MstUser;
use App\Models\TrxDetailReservasi;
use App\Models\TrxReservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_lab'           => MstLaboratorium::count(),
            'lab_aktif'           => MstLaboratorium::where('status', true)->count(),
            'reservasi_pending'   => TrxReservasi::tahunAjaranAktif()->where('status', 'pending')->count(),
            'reservasi_disetujui' => TrxReservasi::tahunAjaranAktif()->where('status', 'disetujui')->count(),
            'total_user'          => MstUser::count(),
        ];

        $reservasiTerbaru = TrxReservasi::tahunAjaranAktif()
            ->with(['user', 'detail.laboratorium'])
            ->latest()
            ->take(8)
            ->get();

        // Jadwal 7 hari ke depan
        $jadwalMendatang = TrxDetailReservasi::with(['laboratorium', 'reservasi.user'])
            ->whereBetween('tanggal_pakai', [
                Carbon::today()->toDateString(),
                Carbon::today()->addDays(6)->toDateString(),
            ])
            ->whereHas('reservasi', function ($q) {
                $q->tahunAjaranAktif()->whereIn('status', ['disetujui', 'sedang_dipakai', 'pending']);
            })
            ->orderBy('tanggal_pakai')
            ->orderBy('jam_mulai')
            ->get();

        // Status lab hari ini
        $labs = MstLaboratorium::where('status', true)
            ->with(['detailReservasi' => function ($q) {
                $q->where('tanggal_pakai', Carbon::today()->toDateString())
                  ->whereHas('reservasi', function ($q2) {
                      $q2->tahunAjaranAktif()->whereIn('status', ['disetujui', 'sedang_dipakai']);
                  })
                  ->with('reservasi.user');
            }])
            ->orderBy('nama_lab')
            ->get();

        return view('admin.dashboard', compact('stats', 'reservasiTerbaru', 'jadwalMendatang', 'labs'));
    }
}
