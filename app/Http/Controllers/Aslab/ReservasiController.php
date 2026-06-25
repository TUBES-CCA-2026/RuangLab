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

        // Cek duplikat reservasi aslab yang sama
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

        // Cek apakah ada peminjam yang sudah disetujui di slot yang sama
        // Jika ada, aslab tetap dibuat tapi statusnya pending (bukan auto-disetujui)
        $adaPeminjamDisetujui = TrxDetailReservasi::where('id_ruangan', $validated['id_ruangan'])
            ->where('tanggal_pakai', $validated['tanggal_pakai'])
            ->whereHas('reservasi', function ($q) {
                $q->whereIn('status', ['disetujui', 'sedang_dipakai'])
                  ->where('is_prioritas', false);
            })
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('jam_mulai', '<=', $validated['jam_mulai'])
                         ->where('jam_selesai', '>=', $validated['jam_selesai']);
                  });
            })->exists();

        $status = $adaPeminjamDisetujui ? 'pending' : 'disetujui';

        DB::transaction(function () use ($validated, $status, &$reservasi) {
            $reservasi = TrxReservasi::create([
                'id_user'           => Auth::id(),
                'kode_checkin'      => 'CHK-' . strtoupper(Str::random(6)),
                'tanggal_pengajuan' => now()->toDateString(),
                'keperluan'         => $validated['keperluan'],
                'status'            => $status,
                'is_prioritas'      => true,
            ]);

            TrxDetailReservasi::create([
                'id_reservasi'  => $reservasi->id,
                'id_ruangan'    => $validated['id_ruangan'],
                'tanggal_pakai' => $validated['tanggal_pakai'],
                'jam_mulai'     => $validated['jam_mulai'],
                'jam_selesai'   => $validated['jam_selesai'],
            ]);
        });

        $msg = $status === 'disetujui'
            ? 'Reservasi berhasil dibuat dan langsung disetujui.'
            : 'Reservasi dibuat dengan status pending karena slot sudah diisi peminjam yang disetujui.';

        return redirect()->route('aslab.reservasi.index')->with('success', $msg);
    }

    public function show($id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->with('detail.laboratorium')
            ->findOrFail($id);

        return view('aslab.reservasi.show', compact('reservasi'));
    }

    public function edit($id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->where('status', 'pending')
            ->with('detail.laboratorium')
            ->findOrFail($id);

        $labs = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();

        return view('aslab.reservasi.edit', compact('reservasi', 'labs'));
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

        return redirect()->route('aslab.reservasi.show', $reservasi->id)
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = TrxReservasi::where('id_user', Auth::id())
            ->whereNotIn('status', ['sedang_dipakai'])
            ->findOrFail($id);

        $reservasi->detail()->delete();
        $reservasi->delete();

        return redirect()->route('aslab.reservasi.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }
}
