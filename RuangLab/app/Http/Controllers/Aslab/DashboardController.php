<?php

namespace App\Http\Controllers\Aslab;

use App\Http\Controllers\Controller;
use App\Models\TrxReservasi;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending'        => TrxReservasi::where('status', 'pending')->count(),
            'disetujui'      => TrxReservasi::where('status', 'disetujui')->count(),
            'ditolak'        => TrxReservasi::where('status', 'ditolak')->count(),
            'sedang_dipakai' => TrxReservasi::where('status', 'sedang_dipakai')->count(),
        ];

        $antrian = TrxReservasi::with(['user', 'detail.laboratorium'])
            ->where('status', 'pending')
            ->oldest()
            ->take(5)
            ->get();

        return view('aslab.dashboard', compact('stats', 'antrian'));
    }
}
