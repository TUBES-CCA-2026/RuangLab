<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MstDay;
use App\Models\MstLaboratorium;
use App\Models\MstMataKuliah;
use App\Models\MstTahunAjaran;
use App\Models\TrxJadwalKuliah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportJadwalController extends Controller
{
    private const KOLOM = ['nama_matkul', 'nama_dosen', 'sks', 'hari', 'jam_mulai', 'jam_selesai', 'id_ruangan'];

    public function index()
    {
        $tahunAjarans = MstTahunAjaran::orderByDesc('tanggal_mulai')->get();
        $labs         = MstLaboratorium::orderBy('nama_lab')->get();

        return view('admin.jadwal.import', compact('tahunAjarans', 'labs'));
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Jadwal');
        $sheet->fromArray(self::KOLOM, null, 'A1');
        $sheet->fromArray(
            ['Praktikum Basis Data', 'Dr. Contoh Dosen', 3, 'senin', '08:00', '10:00', 'ISI-UUID-LABORATORIUM'],
            null,
            'A2'
        );

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'template_jadwal_kuliah.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'id_tahun_ajaran' => ['required', 'exists:mst_tahun_ajaran,id'],
            'file'            => ['required', 'file', 'mimes:xlsx,csv,txt'],
        ]);

        $idTahunAjaran = $request->id_tahun_ajaran;
        $file          = $request->file('file');
        $ekstensi      = strtolower($file->getClientOriginalExtension());

        $baris = $ekstensi === 'xlsx'
            ? $this->bacaXlsx($file->getRealPath())
            : $this->bacaCsv($file->getRealPath());

        $labIds = MstLaboratorium::pluck('id')->all();
        $hariMap = MstDay::all()->mapWithKeys(fn ($d) => [$this->normalisasiHari($d->nama_hari) => $d]);

        $berhasil = 0;
        $errors   = [];

        foreach ($baris as $nomor => $row) {
            $namaMatkul  = trim($row['nama_matkul'] ?? '');
            $namaDosen   = trim($row['nama_dosen'] ?? '');
            $sks         = trim((string) ($row['sks'] ?? ''));
            $hariMentah  = trim($row['hari'] ?? '');
            $idRuangan   = trim($row['id_ruangan'] ?? '');
            $jamMulai    = $this->parseJam($row['jam_mulai'] ?? null);
            $jamSelesai  = $this->parseJam($row['jam_selesai'] ?? null);

            if ($namaMatkul === '' || $namaDosen === '' || $sks === '' || $hariMentah === '' || $idRuangan === '') {
                $errors[] = "Baris {$nomor}: kolom wajib ada yang kosong.";
                continue;
            }
            if (! is_numeric($sks) || (int) $sks < 1) {
                $errors[] = "Baris {$nomor}: SKS harus berupa angka.";
                continue;
            }
            $hari = $hariMap[$this->normalisasiHari($hariMentah)] ?? null;
            if (! $hari) {
                $errors[] = "Baris {$nomor}: nama hari \"{$hariMentah}\" tidak dikenali.";
                continue;
            }
            if (! $jamMulai || ! $jamSelesai) {
                $errors[] = "Baris {$nomor}: format jam mulai/selesai tidak valid.";
                continue;
            }
            if ($jamSelesai <= $jamMulai) {
                $errors[] = "Baris {$nomor}: jam selesai harus setelah jam mulai.";
                continue;
            }
            if (! in_array($idRuangan, $labIds, true)) {
                $errors[] = "Baris {$nomor}: id_ruangan \"{$idRuangan}\" tidak ditemukan.";
                continue;
            }
            if ($this->adaBentrok($idRuangan, $hari->id, $idTahunAjaran, $jamMulai, $jamSelesai)) {
                $errors[] = "Baris {$nomor}: bentrok dengan jadwal lain di lab \"{$idRuangan}\" pada {$hari->nama_hari}.";
                continue;
            }

            DB::transaction(function () use ($namaMatkul, $namaDosen, $sks, $idRuangan, $hari, $idTahunAjaran, $jamMulai, $jamSelesai) {
                $matkul = MstMataKuliah::firstOrCreate(
                    ['nama_matkul' => $namaMatkul, 'nama_dosen' => $namaDosen],
                    ['sks' => (int) $sks, 'frekuensi' => 1]
                );

                TrxJadwalKuliah::create([
                    'id_matkul'       => $matkul->id,
                    'id_lab'          => $idRuangan,
                    'id_day'          => $hari->id,
                    'id_tahun_ajaran' => $idTahunAjaran,
                    'jam_mulai'       => $jamMulai,
                    'jam_selesai'     => $jamSelesai,
                ]);
            });

            $berhasil++;
        }

        return back()
            ->with('success', "Impor selesai: {$berhasil} baris berhasil, " . count($errors) . ' baris gagal.')
            ->with('import_errors', $errors);
    }

    private function bacaCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        $header = null;
        $rows   = [];
        $nomor  = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $nomor++;
            if ($header === null) {
                $header = array_map(fn ($h) => trim(strtolower($h)), $data);
                continue;
            }
            if (count(array_filter($data, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }
            $rows[$nomor] = array_combine($header, array_pad($data, count($header), null));
        }
        fclose($handle);

        return $rows;
    }

    private function bacaXlsx(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $data        = $sheet->toArray(null, true, false, false);

        $header = array_map(fn ($h) => trim(strtolower((string) $h)), array_shift($data));

        $rows  = [];
        $nomor = 1;
        foreach ($data as $line) {
            $nomor++;
            if (count(array_filter($line, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }
            $rows[$nomor] = array_combine($header, array_pad($line, count($header), null));
        }

        return $rows;
    }

    private function parseJam($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('H:i:s');
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            return Carbon::parse(trim((string) $value))->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalisasiHari(string $hari): string
    {
        return str_replace("'", '', strtolower(trim($hari)));
    }

    private function adaBentrok(string $idLab, string $idDay, string $idTahunAjaran, string $jamMulai, string $jamSelesai): bool
    {
        return TrxJadwalKuliah::where('id_lab', $idLab)
            ->where('id_day', $idDay)
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                  ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                  ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                      $q2->where('jam_mulai', '<=', $jamMulai)
                         ->where('jam_selesai', '>=', $jamSelesai);
                  });
            })
            ->exists();
    }
}
