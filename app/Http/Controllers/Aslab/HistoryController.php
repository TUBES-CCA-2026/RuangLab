<?php

namespace App\Http\Controllers\Aslab;

use App\Http\Controllers\Controller;
use App\Models\TrxReservasi;

class HistoryController extends Controller
{
    /**
     * Riwayat semua reservasi yang sudah diproses
     */
    public function index()
    {
        $reservasis = TrxReservasi::with(['user', 'detail.laboratorium'])
            ->whereIn('status', ['disetujui', 'ditolak', 'sedang_dipakai', 'hangus'])
            ->latest()
            ->paginate(15);

        return view('aslab.history.index', compact('reservasis'));
    }
}
