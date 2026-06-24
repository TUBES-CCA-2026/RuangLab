<?php

namespace App\Http\Controllers;

use App\Models\MstLaboratorium;
use App\Models\TrxDetailReservasi;
use App\Models\TrxReservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservasiController extends Controller
{
    public function create(Request $request)
    {
        $labs = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
        $labTerpilih = $request->get('lab');

        return view('reservasi.create', compact('labs', 'labTerpilih'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ruangan'    => ['required', 'exists:mst_laboratorium,id'],
            'keperluan'     => ['required', 'string', 'max:255'],
            'tanggal_pakai' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'     => ['required'],
            'jam_selesai'   => ['required', 'after:jam_mulai'],
            'jam_selesai'   => ['required', 'after:jam_mulai'],
        ]);

        // Kalau tanggal hari ini, jam mulai tidak boleh sudah lewat
if ($validated['tanggal_pakai'] === now()->toDateString()) {
    if ($validated['jam_mulai'] <= now()->format('H:i')) {
        return back()->withInput()->withErrors([
            'jam_mulai' => 'Jam mulai sudah lewat. Pilih jam yang belum terjadi hari ini.',
        ]);
    }
}
        // Jam selesai maksimal 18:10
        if ($validated['jam_selesai'] > '18:10') {
            return back()->withInput()->withErrors([
                'jam_selesai' => 'Reservasi hanya dapat dilakukan hingga pukul 18:10.',
            ]);
        }

        // Cek duplikat reservasi user yang sama pada slot yang sama
        $duplikat = TrxDetailReservasi::where('id_ruangan', $validated['id_ruangan'])
            ->where('tanggal_pakai', $validated['tanggal_pakai'])
            ->whereHas('reservasi', function ($q) {
                $q->where('id_user', Auth::id())
                  ->whereNotIn('status', ['ditolak', 'hangus']);
            })
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('jam_mulai', '<=', $validated['jam_mulai'])
                         ->where('jam_selesai', '>=', $validated['jam_selesai']);
                  });
            })->exists();

        if ($duplikat) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Anda sudah memiliki reservasi untuk lab dan waktu yang sama.',
            ]);
        }

        // Cek bentrok dengan reservasi lain yang sudah disetujui
        $bentrok = TrxDetailReservasi::where('id_ruangan', $validated['id_ruangan'])
            ->where('tanggal_pakai', $validated['tanggal_pakai'])
            ->whereHas('reservasi', function ($q) {
                $q->whereIn('status', ['disetujui', 'sedang_dipakai']);
            })
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('jam_mulai', '<=', $validated['jam_mulai'])
                         ->where('jam_selesai', '>=', $validated['jam_selesai']);
                  });
            })->exists();

        if ($bentrok) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Laboratorium sudah dibooking pada waktu tersebut. Silakan pilih waktu lain.',
            ]);
        }

        DB::transaction(function () use ($validated, &$reservasi) {
            $reservasi = TrxReservasi::create([
                'id_user'           => Auth::id(),
                'kode_checkin'      => 'CHK-' . strtoupper(Str::random(6)),
                'tanggal_pengajuan' => now()->toDateString(),
                'keperluan'         => $validated['keperluan'],
                'status'            => 'pending',
            ]);

            TrxDetailReservasi::create([
                'id_reservasi'  => $reservasi->id,
                'id_ruangan'    => $validated['id_ruangan'],
                'tanggal_pakai' => $validated['tanggal_pakai'],
                'jam_mulai'     => $validated['jam_mulai'],
                'jam_selesai'   => $validated['jam_selesai'],
            ]);
        });

        return redirect()->route('reservasi.index')
            ->with('success', 'Pengajuan reservasi berhasil dikirim. Mohon tunggu persetujuan admin.');
    }

    public function index()
    {
        $reservasis = TrxReservasi::where('id_user', Auth::id())
            ->with('detail.laboratorium')
            ->latest()
            ->paginate(10);

        return view('reservasi.index', compact('reservasis'));
    }

    public function show($id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->with('detail.laboratorium')
            ->findOrFail($id);

        return view('reservasi.show', compact('reservasi'));
    }

    public function edit($id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->where('status', 'pending')
            ->with('detail.laboratorium')
            ->findOrFail($id);

        $labs = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();

        return view('reservasi.edit', compact('reservasi', 'labs'));
    }

    public function update(Request $request, $id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'id_ruangan'    => ['required', 'exists:mst_laboratorium,id'],
            'keperluan'     => ['required', 'string', 'max:255'],
            'tanggal_pakai' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'     => ['required'],
            'jam_selesai'   => ['required', 'after:jam_mulai'],
        ]);

        if ($validated['jam_selesai'] > '18:10') {
            return back()->withInput()->withErrors([
                'jam_selesai' => 'Reservasi hanya dapat dilakukan hingga pukul 18:10.',
            ]);
        }

        // Cek bentrok (kecuali reservasi ini sendiri)
        $bentrok = TrxDetailReservasi::where('id_ruangan', $validated['id_ruangan'])
            ->where('tanggal_pakai', $validated['tanggal_pakai'])
            ->where('id_reservasi', '!=', $reservasi->id)
            ->whereHas('reservasi', function ($q) {
                $q->whereIn('status', ['disetujui', 'sedang_dipakai']);
            })
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('jam_mulai', '<=', $validated['jam_mulai'])
                         ->where('jam_selesai', '>=', $validated['jam_selesai']);
                  });
            })->exists();

        if ($bentrok) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Laboratorium sudah dibooking pada waktu tersebut.',
            ]);
        }

        DB::transaction(function () use ($reservasi, $validated) {
            $reservasi->update(['keperluan' => $validated['keperluan']]);

            $reservasi->detail()->updateOrCreate(
                ['id_reservasi' => $reservasi->id],
                [
                    'id_ruangan'    => $validated['id_ruangan'],
                    'tanggal_pakai' => $validated['tanggal_pakai'],
                    'jam_mulai'     => $validated['jam_mulai'],
                    'jam_selesai'   => $validated['jam_selesai'],
                ]
            );
        });

        return redirect()->route('reservasi.show', $reservasi->id)
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $reservasi->detail()->delete();
        $reservasi->delete();

        return redirect()->route('reservasi.index')
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
