<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RekapExport;
use App\Http\Controllers\Controller;
use App\Models\MstLaboratorium;
use App\Models\MstMataKuliah;
use App\Models\MstTahunAjaran;
use App\Models\TrxDetailReservasi;
use App\Models\TrxJadwalKuliah;
use App\Models\TrxReservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->hitungRekap($request);

        $tahunAjarans = MstTahunAjaran::orderByDesc('tanggal_mulai')->get();
        $labs         = MstLaboratorium::orderBy('nama_lab')->get();
        $matkuls      = MstMataKuliah::orderBy('nama_matkul')->get();

        return view('admin.rekap.index', compact('data', 'tahunAjarans', 'labs', 'matkuls'));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->hitungRekap($request);

        $pdf = Pdf::loadView('admin.rekap.pdf', [
            'data'   => $data,
            'filter' => $this->labelFilter($request),
        ]);

        return $pdf->download('rekap-laboratorium-' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->hitungRekap($request);

        return Excel::download(
            new RekapExport($data, $this->labelFilter($request)),
            'rekap-laboratorium-' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    /**
     * Hitung 5 metrik rekap berdasarkan filter tahun ajaran, laboratorium, dan mata kuliah.
     */
    private function hitungRekap(Request $request): array
    {
        $tahunAjaran = $request->filled('tahun_ajaran_id')
            ? MstTahunAjaran::find($request->tahun_ajaran_id)
            : null;

        $base = TrxDetailReservasi::query()
            ->when($tahunAjaran, fn($q) => $q->whereBetween('tanggal_pakai', [$tahunAjaran->tanggal_mulai, $tahunAjaran->tanggal_selesai]))
            ->when($request->filled('id_lab'), fn($q) => $q->where('id_ruangan', $request->id_lab))
            ->when($request->filled('id_matkul'), fn($q) => $q->where('id_matkul', $request->id_matkul));

        $jumlahReservasi = (clone $base)
            ->whereHas('reservasi', fn($q) => $q->whereIn('status', ['pending', 'disetujui', 'sedang_dipakai', 'selesai']))
            ->count();

        $dipakaiQuery = (clone $base)
            ->whereHas('reservasi', fn($q) => $q->whereIn('status', ['sedang_dipakai', 'selesai']));

        $jumlahPenggunaan = (clone $dipakaiQuery)->count();

        $jamPenggunaan = (clone $dipakaiQuery)->get()->sum(function ($d) {
            return Carbon::parse($d->jam_mulai)->diffInMinutes(Carbon::parse($d->jam_selesai)) / 60;
        });

        $jumlahDitolak = (clone $base)
            ->whereHas('reservasi', fn($q) => $q->where('status', 'ditolak'))
            ->count();

        $jumlahPraktikum = TrxJadwalKuliah::query()
            ->when($request->filled('id_lab'), fn($q) => $q->where('id_lab', $request->id_lab))
            ->when($request->filled('id_matkul'), fn($q) => $q->where('id_matkul', $request->id_matkul))
            ->count();

        // Reservasi yang dibatalkan sendiri oleh peminjam (soft-deleted): baris trx_detail_reservasi
        // sudah hard-deleted saat dibatalkan, sehingga tidak bisa diatribusikan ke lab/mata kuliah
        // tertentu. Hanya dihitung ke total kalau filter Lab & Mata Kuliah sedang tidak aktif.
        $jumlahDibatalkanSendiri = ($request->filled('id_lab') || $request->filled('id_matkul'))
            ? 0
            : TrxReservasi::onlyTrashed()
                ->where('status', 'pending')
                ->when($tahunAjaran, fn($q) => $q->whereBetween('tanggal_pengajuan', [$tahunAjaran->tanggal_mulai, $tahunAjaran->tanggal_selesai]))
                ->count();

        $jumlahPembatalan = $jumlahDitolak + $jumlahDibatalkanSendiri;

        return [
            'jumlah_praktikum'          => $jumlahPraktikum,
            'jumlah_penggunaan_lab'     => $jumlahPenggunaan,
            'jumlah_reservasi'          => $jumlahReservasi,
            'jumlah_pembatalan'         => $jumlahPembatalan,
            'jam_penggunaan_lab'        => round($jamPenggunaan, 1),
            'ada_filter_lab_atau_matkul' => $request->filled('id_lab') || $request->filled('id_matkul'),
        ];
    }

    private function labelFilter(Request $request): array
    {
        $tahunAjaran = $request->filled('tahun_ajaran_id') ? MstTahunAjaran::find($request->tahun_ajaran_id) : null;
        $lab         = $request->filled('id_lab') ? MstLaboratorium::find($request->id_lab) : null;
        $matkul      = $request->filled('id_matkul') ? MstMataKuliah::find($request->id_matkul) : null;

        return [
            'tahun_ajaran' => $tahunAjaran->nama ?? 'Semua Periode',
            'laboratorium' => $lab->nama_lab ?? 'Semua Laboratorium',
            'mata_kuliah'  => $matkul->nama_matkul ?? 'Semua Mata Kuliah',
        ];
    }
}
