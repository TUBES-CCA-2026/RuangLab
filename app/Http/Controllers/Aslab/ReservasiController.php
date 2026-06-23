<?php

namespace App\Http\Controllers\Aslab;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\TrxDetailReservasi;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = TrxReservasi::where('id_user', Auth::id())
            ->with('detail.laboratorium')
            ->latest()
            ->paginate(10);

        return view('aslab.reservasi.index', compact('reservasis'));
    }

    public function create(Request $request)
    {
        $labs = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
        $labTerpilih = $request->get('lab');

        return view('aslab.reservasi.create', compact('labs', 'labTerpilih'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ruangan'   => ['required', 'exists:mst_laboratorium,id'],
            'keperluan'    => ['required', 'string', 'max:255'],
            'tanggal_pakai' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'    => ['required'],
            'jam_selesai'  => ['required', 'after:jam_mulai'],
        ]);

        DB::transaction(function () use ($validated, &$reservasi) {
            $reservasi = TrxReservasi::create([
                'id_user'           => Auth::id(),
                'kode_reservasi'    => 'RSV-' . strtoupper(Str::random(8)),
                'kode_checkin'      => 'CHK-' . strtoupper(Str::random(6)),
                'tanggal_pengajuan' => now()->toDateString(),
                'keperluan'         => $validated['keperluan'],
                'status'            => 'disetujui',  // auto-disetujui karena prioritas
                'is_prioritas'      => true,
            ]);

            TrxDetailReservasi::create([
                'id_reservasi' => $reservasi->id,
                'id_ruangan'   => $validated['id_ruangan'],
                'tanggal_pakai' => $validated['tanggal_pakai'],
                'jam_mulai'    => $validated['jam_mulai'],
                'jam_selesai'  => $validated['jam_selesai'],
            ]);
        });

        return redirect()->route('aslab.reservasi.index')
            ->with('success', 'Reservasi berhasil dibuat dan langsung disetujui.');
    }

    public function show($id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->with('detail.laboratorium')
            ->findOrFail($id);

        return view('aslab.reservasi.show', compact('reservasi'));
    }
}
