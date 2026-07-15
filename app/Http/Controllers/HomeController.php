<?php

namespace App\Http\Controllers;

use App\Models\MstLaboratorium;
use App\Models\TrxDetailReservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalLab = MstLaboratorium::where('status', true)->count();
        $labUnggulan = MstLaboratorium::where('status', true)
            ->withCount('detailReservasi')
            ->latest()
            ->take(3)
            ->get();

        // Jadwal lab yang sudah direservasi untuk 7 hari ke depan (tanpa data pribadi peminjam)
        $jadwalMendatang = TrxDetailReservasi::with(['laboratorium', 'reservasi'])
            ->whereBetween('tanggal_pakai', [
                Carbon::today()->toDateString(),
                Carbon::today()->addDays(6)->toDateString(),
            ])
            ->whereHas('reservasi', function ($q) {
                $q->whereIn('status', ['disetujui', 'sedang_dipakai']);
            })
            ->orderBy('tanggal_pakai')
            ->orderBy('jam_mulai')
            ->get();

        return view('home', compact('totalLab', 'labUnggulan', 'jadwalMendatang'));
    }
}
