<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxJadwalKuliah extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'trx_jadwal_kuliah';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_matkul', 'id_lab', 'id_day', 'id_tahun_ajaran', 'jam_mulai', 'jam_selesai'];

    public function mataKuliah()
    {
        return $this->belongsTo(MstMataKuliah::class, 'id_matkul');
    }

    public function laboratorium()
    {
        return $this->belongsTo(MstLaboratorium::class, 'id_lab');
    }

    public function hari()
    {
        return $this->belongsTo(MstDay::class, 'id_day');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(MstTahunAjaran::class, 'id_tahun_ajaran');
    }

    /**
     * Cek apakah rentang jam di lab & tanggal tertentu bentrok dengan jadwal kuliah
     * pada tahun ajaran yang sedang aktif (tanggal harus berada di rentang tahun ajaran tsb).
     */
    public static function bentrokDenganReservasi(string $idLab, string $tanggalPakai, string $jamMulai, string $jamSelesai): bool
    {
        $tahunAjaranAktif = MstTahunAjaran::aktif();

        if (! $tahunAjaranAktif) {
            return false;
        }

        $tanggal = \Carbon\Carbon::parse($tanggalPakai)->startOfDay();

        if ($tanggal->lt($tahunAjaranAktif->tanggal_mulai) || $tanggal->gt($tahunAjaranAktif->tanggal_selesai)) {
            return false;
        }

        $namaHari = MstDay::URUTAN[$tanggal->dayOfWeekIso - 1];

        return static::where('id_lab', $idLab)
            ->where('id_tahun_ajaran', $tahunAjaranAktif->id)
            ->whereHas('hari', fn ($q) => $q->where('nama_hari', $namaHari))
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
