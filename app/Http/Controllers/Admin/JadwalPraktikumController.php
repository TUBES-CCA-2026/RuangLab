<?php

namespace App\Http\Controllers\Admin;

use App\Exports\JadwalPraktikumExport;
use App\Http\Controllers\Controller;
use App\Models\MstDay;
use App\Models\MstLaboratorium;
use App\Models\MstMataKuliah;
use App\Models\MstTahunAjaran;
use App\Models\TrxJadwalKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class JadwalPraktikumController extends Controller
{
    public function index(Request $request)
    {
        $lihatTerhapus = $request->boolean('terhapus');

        $query = $lihatTerhapus
            ? TrxJadwalKuliah::onlyTrashed()->with(['mataKuliah', 'laboratorium', 'hari', 'tahunAjaran'])
            : TrxJadwalKuliah::with(['mataKuliah', 'laboratorium', 'hari', 'tahunAjaran']);

        if ($request->filled('id_day')) {
            $query->where('id_day', $request->id_day);
        }
        if ($request->filled('id_lab')) {
            $query->where('id_lab', $request->id_lab);
        }
        // Sudah pernah pilih filter tahun ajaran secara eksplisit (termasuk memilih
        // "Semua Tahun Ajaran")? Kalau belum sama sekali (kunjungan pertama), default
        // ke tahun ajaran yang sedang aktif supaya halaman ini "bersih" per periode.
        if ($request->filled('id_tahun_ajaran')) {
            $tahunAjaranTerpilih = $request->id_tahun_ajaran;
        } elseif (!$request->hasAny(['id_day', 'id_lab', 'id_tahun_ajaran'])) {
            $tahunAjaranTerpilih = MstTahunAjaran::aktif()?->id;
        } else {
            $tahunAjaranTerpilih = null;
        }

        if ($tahunAjaranTerpilih) {
            $query->where('id_tahun_ajaran', $tahunAjaranTerpilih);
        }

        $jadwals = $query->get()->sortBy([
            fn ($j) => array_search($j->hari->nama_hari ?? '', MstDay::URUTAN),
            fn ($j) => $j->jam_mulai,
        ])->values();

        $hariList     = $this->hariTerurut();
        $labs         = MstLaboratorium::orderBy('nama_lab')->get();
        $tahunAjarans = MstTahunAjaran::orderByDesc('tanggal_mulai')->get();

        return view('admin.jadwal-praktikum.index', compact('jadwals', 'hariList', 'labs', 'tahunAjarans', 'tahunAjaranTerpilih', 'lihatTerhapus'));
    }

    public function export(Request $request)
    {
        $query = TrxJadwalKuliah::with(['mataKuliah', 'laboratorium', 'hari', 'tahunAjaran']);

        if ($request->filled('id_day')) {
            $query->where('id_day', $request->id_day);
        }
        if ($request->filled('id_lab')) {
            $query->where('id_lab', $request->id_lab);
        }
        if ($request->filled('id_tahun_ajaran')) {
            $query->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        }

        $jadwals = $query->get()->sortBy([
            fn ($j) => array_search($j->hari->nama_hari ?? '', MstDay::URUTAN),
            fn ($j) => $j->jam_mulai,
        ])->values();

        $filename = 'jadwal-praktikum-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new JadwalPraktikumExport($jadwals), $filename);
    }

    public function create()
    {
        $hariList     = $this->hariTerurut();
        $labs         = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
        $tahunAjarans = MstTahunAjaran::orderByDesc('tanggal_mulai')->get();

        return view('admin.jadwal-praktikum.create', compact('hariList', 'labs', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        if ($bentrok = $this->cekBentrok($validated)) {
            return back()->withInput()->withErrors(['jam_mulai' => $bentrok]);
        }

        DB::transaction(function () use ($validated) {
            $matkul = MstMataKuliah::firstOrCreate(
                [
                    'nama_matkul' => $validated['nama_matkul'],
                    'nama_dosen'  => $validated['nama_dosen'],
                ],
                [
                    'sks'       => $validated['sks'] ?? 1,
                    'frekuensi' => 1,
                ]
            );

            TrxJadwalKuliah::create([
                'id_matkul'       => $matkul->id,
                'id_lab'          => $validated['id_lab'],
                'id_day'          => $validated['id_day'],
                'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
                'jam_mulai'       => $validated['jam_mulai'],
                'jam_selesai'     => $validated['jam_selesai'],
            ]);
        });

        return redirect()->route('admin.jadwal-praktikum.index')
            ->with('success', 'Jadwal praktikum berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal       = TrxJadwalKuliah::with('mataKuliah')->findOrFail($id);
        $hariList     = $this->hariTerurut();
        $labs         = MstLaboratorium::where('status', true)->orderBy('nama_lab')->get();
        $tahunAjarans = MstTahunAjaran::orderByDesc('tanggal_mulai')->get();

        return view('admin.jadwal-praktikum.edit', compact('jadwal', 'hariList', 'labs', 'tahunAjarans'));
    }

    public function update(Request $request, $id)
    {
        $jadwal    = TrxJadwalKuliah::findOrFail($id);
        $validated = $this->validasi($request);

        if ($bentrok = $this->cekBentrok($validated, $jadwal->id)) {
            return back()->withInput()->withErrors(['jam_mulai' => $bentrok]);
        }

        DB::transaction(function () use ($validated, $jadwal) {
            $matkul = MstMataKuliah::firstOrCreate(
                [
                    'nama_matkul' => $validated['nama_matkul'],
                    'nama_dosen'  => $validated['nama_dosen'],
                ],
                [
                    'sks'       => $validated['sks'] ?? 1,
                    'frekuensi' => 1,
                ]
            );

            $jadwal->update([
                'id_matkul'       => $matkul->id,
                'id_lab'          => $validated['id_lab'],
                'id_day'          => $validated['id_day'],
                'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
                'jam_mulai'       => $validated['jam_mulai'],
                'jam_selesai'     => $validated['jam_selesai'],
            ]);
        });

        return redirect()->route('admin.jadwal-praktikum.index')
            ->with('success', 'Jadwal praktikum berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = TrxJadwalKuliah::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal-praktikum.index')
            ->with('success', 'Jadwal praktikum berhasil dihapus.');
    }

    public function restore($id)
    {
        $jadwal = TrxJadwalKuliah::onlyTrashed()->findOrFail($id);
        $jadwal->restore();

        // back() supaya tetap di filter hari/lab/tahun ajaran yang sedang dilihat.
        return back()->with('success', 'Jadwal praktikum berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $jadwal = TrxJadwalKuliah::onlyTrashed()->findOrFail($id);
        $jadwal->forceDelete();

        return back()->with('success', 'Jadwal praktikum berhasil dihapus permanen.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama_matkul' => ['required', 'string', 'max:255'],
            'nama_dosen'  => ['required', 'string', 'max:255'],
            'sks'         => ['nullable', 'integer', 'min:1', 'max:10'],
            'id_lab'          => ['required', 'exists:mst_laboratorium,id'],
            'id_day'          => ['required', 'exists:mst_day,id'],
            'id_tahun_ajaran' => ['required', 'exists:mst_tahun_ajaran,id'],
            'jam_mulai'       => ['required'],
            'jam_selesai'     => ['required', 'after:jam_mulai'],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);
    }

    private function cekBentrok(array $validated, ?string $kecualiId = null): ?string
    {
        $bentrok = TrxJadwalKuliah::where('id_lab', $validated['id_lab'])
            ->where('id_day', $validated['id_day'])
            ->when($kecualiId, fn ($q) => $q->where('id', '!=', $kecualiId))
            ->where(function ($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('jam_mulai', '<=', $validated['jam_mulai'])
                         ->where('jam_selesai', '>=', $validated['jam_selesai']);
                  });
            })->exists();

        return $bentrok ? 'Lab sudah terpakai jadwal praktikum lain pada hari dan jam tersebut.' : null;
    }

    private function hariTerurut()
    {
        return MstDay::all()->sortBy(
            fn ($d) => array_search($d->nama_hari, MstDay::URUTAN)
        )->values();
    }
}
