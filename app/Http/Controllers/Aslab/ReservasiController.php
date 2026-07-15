<?php



namespace App\Http\Controllers\Aslab;

use App\Http\Controllers\Controller; // ← tambahkan baris ini
use App\Models\MstLaboratorium;
use App\Models\MstUser;
use App\Models\TrxDetailReservasi;
use App\Models\TrxReservasi;
use App\Notifications\ReservasiDibuat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        TrxReservasi::autoCompleteExpired();

        $query = TrxReservasi::where('id_user', Auth::id())
            ->with('detail.laboratorium');

        // Tab aktif = pending, disetujui, sedang_dipakai
        // Tab history = ditolak, hangus, selesai
        if ($request->get('tab') === 'history') {
            $query->whereIn('status', ['ditolak', 'hangus', 'selesai']);
        } else {
            $query->whereIn('status', ['pending', 'disetujui', 'sedang_dipakai']);
        }

        $reservasis = $query->latest()->paginate(10)->withQueryString();

        return view('reservasi.index', compact('reservasis'));
    }

    public function create(Request $request)
    {
        $labs        = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
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

        // Kalau tanggal hari ini, jam mulai tidak boleh sudah lewat
        if ($validated['tanggal_pakai'] === now()->toDateString()) {
            if ($validated['jam_mulai'] <= now()->format('H:i')) {
                return back()->withInput()->withErrors([
                    'jam_mulai' => 'Jam mulai sudah lewat. Pilih jam yang belum terjadi hari ini.',
                ]);
            }
        }

        if ($validated['jam_selesai'] > '18:10') {
            return back()->withInput()->withErrors([
                'jam_selesai' => 'Reservasi hanya dapat dilakukan hingga pukul 18:10.',
            ]);
        }

        // Cek duplikat reservasi user yang sama
        $duplikat = TrxDetailReservasi::where('id_ruangan', $validated['id_ruangan'])
            ->where('tanggal_pakai', $validated['tanggal_pakai'])
            ->whereHas('reservasi', function ($q) {
                $q->where('id_user', Auth::id())
                  ->whereNotIn('status', ['ditolak', 'hangus']);
            })
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(fn($q2) => $q2->where('jam_mulai', '<=', $validated['jam_mulai'])
                                          ->where('jam_selesai', '>=', $validated['jam_selesai']));
            })->exists();

        if ($duplikat) {
            return back()->withInput()->withErrors([
                'jam_mulai' => 'Anda sudah memiliki reservasi untuk lab dan waktu yang sama.',
            ]);
        }

        // Cek bentrok + simpan di dalam satu transaksi dengan lock untuk cegah race condition
        $bentrokMessage = null;
        try {
            DB::transaction(function () use ($validated, &$reservasi, &$bentrokMessage) {
                $bentrok = TrxDetailReservasi::where('id_ruangan', $validated['id_ruangan'])
                    ->where('tanggal_pakai', $validated['tanggal_pakai'])
                    ->whereHas('reservasi', fn($q) => $q->whereIn('status', ['disetujui', 'sedang_dipakai']))
                    ->where(function ($q) use ($validated) {
                        $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                          ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                          ->orWhere(fn($q2) => $q2->where('jam_mulai', '<=', $validated['jam_mulai'])
                                                   ->where('jam_selesai', '>=', $validated['jam_selesai']));
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($bentrok) {
                    $bentrokMessage = 'Laboratorium sudah dibooking pada waktu tersebut. Silakan pilih waktu lain.';
                    return;
                }

                $reservasi = TrxReservasi::create([
                    'id_user'           => Auth::id(),
                    'kode_checkin'      => 'CHK-' . strtoupper(Str::random(6)),
                    'tanggal_pengajuan' => now()->toDateString(),
                    'keperluan'         => $validated['keperluan'],
                    'status'            => 'disetujui',
                ]);

                TrxDetailReservasi::create([
                    'id_reservasi'  => $reservasi->id,
                    'id_ruangan'    => $validated['id_ruangan'],
                    'tanggal_pakai' => $validated['tanggal_pakai'],
                    'jam_mulai'     => $validated['jam_mulai'],
                    'jam_selesai'   => $validated['jam_selesai'],
                ]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }

        if ($bentrokMessage) {
            return back()->withInput()->withErrors(['jam_mulai' => $bentrokMessage]);
        }

<<<<<<< HEAD
        return redirect()->route('aslab.dashboard')
            ->with('success', 'Reservasi berhasil dibuat dan langsung disetujui.');
=======
            TrxDetailReservasi::create([
                'id_reservasi'  => $reservasi->id,
                'id_ruangan'    => $validated['id_ruangan'],
                'tanggal_pakai' => $validated['tanggal_pakai'],
                'jam_mulai'     => $validated['jam_mulai'],
                'jam_selesai'   => $validated['jam_selesai'],
            ]);
        });

        // Kirim notifikasi ke semua laboran
        $laboran = MstUser::whereHas('role', function ($q) {
            $q->whereRaw('LOWER(nama_role) = ?', ['laboran']);
        })->get();

        foreach ($laboran as $l) {
            try {
                $l->notify(new ReservasiDibuat($reservasi->load('detail.laboratorium', 'user')));
            } catch (\Exception $e) {}
        }

        return redirect()->route('reservasi.index')
            ->with('success', 'Pengajuan reservasi berhasil dikirim. Mohon tunggu persetujuan laboran.');
>>>>>>> eb0f212 (revisi)
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
