<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstTahunAjaran;
use App\Models\TrxDetailReservasi;
use App\Models\TrxReservasi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjarans = MstTahunAjaran::orderByDesc('tanggal_mulai')->get();

        $tahunAjaran = $request->filled('tahun_ajaran')
            ? $tahunAjarans->firstWhere('id', $request->tahun_ajaran)
            : ($tahunAjarans->firstWhere('is_aktif', true) ?? $tahunAjarans->first());

        if (! $tahunAjaran) {
            return view('admin.rekap.index', [
                'tahunAjarans'   => $tahunAjarans,
                'tahunAjaran'    => null,
                'totalReservasi' => 0,
                'perStatus'      => collect(),
                'topLabs'        => collect(),
                'topPeminjam'    => collect(),
                'totalHangus'    => 0,
                'perBulan'       => collect(),
            ]);
        }

        $reservasis = TrxReservasi::where('id_tahun_ajaran', $tahunAjaran->id)->get();

        $totalReservasi = $reservasis->count();

        $perStatus = collect(['pending', 'disetujui', 'ditolak', 'sedang_dipakai', 'hangus', 'selesai'])
            ->mapWithKeys(fn ($status) => [$status => $reservasis->where('status', $status)->count()]);

        $totalHangus = $perStatus['hangus'] ?? 0;

        $topLabs = TrxDetailReservasi::selectRaw('id_ruangan, count(*) as total')
            ->whereHas('reservasi', fn ($q) => $q->where('id_tahun_ajaran', $tahunAjaran->id))
            ->groupBy('id_ruangan')
            ->orderByDesc('total')
            ->with('laboratorium')
            ->take(3)
            ->get();

        $topPeminjam = TrxReservasi::selectRaw('id_user, count(*) as total')
            ->where('id_tahun_ajaran', $tahunAjaran->id)
            ->groupBy('id_user')
            ->orderByDesc('total')
            ->with('user')
            ->take(5)
            ->get();

        $perBulan = collect();
        $periode  = CarbonPeriod::create(
            $tahunAjaran->tanggal_mulai->copy()->startOfMonth(),
            '1 month',
            $tahunAjaran->tanggal_selesai->copy()->startOfMonth()
        );

        foreach ($periode as $bulan) {
            $jumlah = $reservasis->filter(function ($r) use ($bulan) {
                $tanggal = Carbon::parse($r->tanggal_pengajuan);

                return $tanggal->isSameMonth($bulan) && $tanggal->isSameYear($bulan);
            })->count();

            $perBulan->push([
                'label' => $bulan->translatedFormat('M Y'),
                'total' => $jumlah,
            ]);
        }

        return view('admin.rekap.index', compact(
            'tahunAjarans', 'tahunAjaran', 'totalReservasi', 'perStatus',
            'topLabs', 'topPeminjam', 'totalHangus', 'perBulan'
        ));
    }
}
