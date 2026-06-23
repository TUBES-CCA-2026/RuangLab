<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\MstUser;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_lab' => MstLaboratorium::count(),
            'lab_aktif' => MstLaboratorium::where('status', true)->count(),
            'reservasi_pending' => TrxReservasi::where('status', 'pending')->count(),
            'reservasi_disetujui' => TrxReservasi::where('status', 'disetujui')->count(),
            'total_user' => MstUser::count(),
        ];

        $reservasiTerbaru = TrxReservasi::with(['user', 'detail.laboratorium'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'reservasiTerbaru'));
    }
}
