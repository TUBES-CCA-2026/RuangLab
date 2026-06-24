<?php

namespace App\Http\Controllers;

use App\Models\TrxReservasi;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $reservasis = TrxReservasi::with(['detail.laboratorium'])
            ->where('id_user', Auth::id())
            ->whereIn('status', ['disetujui', 'ditolak', 'sedang_dipakai', 'hangus'])
            ->latest()
            ->paginate(10);

        return view('history.index', compact('reservasis'));
    }
}