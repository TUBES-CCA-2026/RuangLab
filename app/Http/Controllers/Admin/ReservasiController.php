<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\MstUser;
use App\Models\TrxDetailReservasi;
use App\Models\TrxJadwalKuliah;
use App\Models\TrxReservasi;
use App\Notifications\ReservasiStatusChanged; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $query = TrxReservasi::with(['user', 'detail.laboratorium']);

        if ($request->filled('cari')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservasis = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function show($id)
    {
        $reservasi = TrxReservasi::with(['user', 'detail.laboratorium'])->findOrFail($id);
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function create()
    {
        $labs  = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
        $users = MstUser::orderBy('nama')->get();
        return view('admin.reservasi.create', compact('labs', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ruangan'    => ['required', 'exists:mst_laboratorium,id'],
            'keperluan'     => ['required', 'string', 'max:255'],
            'tanggal_pakai' => ['required', 'date'],
            'jam_mulai'     => ['required'],
            'jam_selesai'   => ['required', 'after:jam_mulai'],
           
], [
    'jam_selesai.after' => 'Jam selesai harus lebih dari jam mulai.',
]);
    

        if ($validated['jam_selesai'] > '18:10') {
            return back()->withInput()->withErrors([
                'jam_selesai' => 'Reservasi hanya dapat dilakukan hingga pukul 18:10.',
            ]);
        }

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
                'jam_mulai' => 'Laboratorium sudah dibooking pada waktu tersebut.',
            ]);
        }

        if (TrxJadwalKuliah::bentrokDenganReservasi($validated['id_ruangan'], $validated['tanggal_pakai'], $validated['jam_mulai'], $validated['jam_selesai'])) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Laboratorium sedang dipakai untuk jadwal praktikum kuliah pada waktu tersebut.',
            ]);
        }

        DB::transaction(function () use ($validated) {
            $reservasi = TrxReservasi::create([
                'id_user'           => Auth::id(),
                'kode_checkin'      => 'CHK-' . strtoupper(Str::random(6)),
                'tanggal_pengajuan' => now()->toDateString(),
                'keperluan'         => $validated['keperluan'],
                'status'            => 'disetujui',
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

        return redirect()->route('admin.reservasi.index')
            ->with('success', 'Reservasi berhasil dibuat dan langsung disetujui.');
    }

    public function edit($id)
    {
        $reservasi = TrxReservasi::with(['user', 'detail.laboratorium'])->findOrFail($id);
        $labs      = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
        return view('admin.reservasi.edit', compact('reservasi', 'labs'));
    }

    public function update(Request $request, $id)
    {
        $reservasi = TrxReservasi::findOrFail($id);

        $validated = $request->validate([
            'id_ruangan'    => ['required', 'exists:mst_laboratorium,id'],
            'keperluan'     => ['required', 'string', 'max:255'],
            'tanggal_pakai' => ['required', 'date'],
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

        if (TrxJadwalKuliah::bentrokDenganReservasi($validated['id_ruangan'], $validated['tanggal_pakai'], $validated['jam_mulai'], $validated['jam_selesai'])) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Laboratorium sedang dipakai untuk jadwal praktikum kuliah pada waktu tersebut.',
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

        return redirect()->route('admin.reservasi.show', $reservasi->id)
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = TrxReservasi::findOrFail($id);
        $reservasi->detail()->delete();
        $reservasi->delete();

        return redirect()->route('admin.reservasi.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'        => ['required', 'in:disetujui,ditolak,sedang_dipakai,hangus,selesai'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $reservasi = TrxReservasi::with(['user', 'detail.laboratorium'])->findOrFail($id);

        // Cek bentrok saat laboran menyetujui reservasi
        if ($request->status === 'disetujui') {
            $detail = $reservasi->detail->first();
            if ($detail) {
                $bentrok = TrxDetailReservasi::where('id_ruangan', $detail->id_ruangan)
                    ->where('tanggal_pakai', $detail->tanggal_pakai)
                    ->where('id_reservasi', '!=', $reservasi->id)
                    ->whereHas('reservasi', fn($q) => $q->whereIn('status', ['disetujui', 'sedang_dipakai']))
                    ->where(function ($q) use ($detail) {
                        $q->whereBetween('jam_mulai', [$detail->jam_mulai, $detail->jam_selesai])
                          ->orWhereBetween('jam_selesai', [$detail->jam_mulai, $detail->jam_selesai])
                          ->orWhere(fn($q2) => $q2->where('jam_mulai', '<=', $detail->jam_mulai)
                                                   ->where('jam_selesai', '>=', $detail->jam_selesai));
                    })->exists();

                if ($bentrok) {
                    return back()->with('error', 'Tidak dapat menyetujui: lab sudah dibooking pada waktu yang sama.');
                }
            }
        }

        $reservasi->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Notifikasi ke peminjam
        if ($reservasi->user) {
            try {
                $reservasi->user->notify(new ReservasiStatusChanged($reservasi));
            } catch (\Exception $e) {}
        }

        // Notifikasi ke semua aslab
        $aslabs = MstUser::whereHas('role', function ($q) {
            $q->whereRaw('LOWER(nama_role) = ?', ['aslab']);
        })->get();

        foreach ($aslabs as $aslab) {
            try {
                $aslab->notify(new ReservasiStatusChanged($reservasi));
            } catch (\Exception $e) {}
        }

        return redirect()->route('admin.reservasi.show', $reservasi->id)
            ->with('success', 'Status reservasi berhasil diperbarui.');
    }
}