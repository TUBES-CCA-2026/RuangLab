<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $fillable = ['id_matkul', 'id_lab', 'id_day', 'jam_mulai', 'jam_selesai'];

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

    /**
     * Cek apakah lab punya jadwal praktikum tetap yang bentrok dengan waktu reservasi
     * yang diajukan (hari + jam dihitung dari tanggal_pakai).
     */
    public static function bentrokDenganReservasi(string $idLab, string $tanggalPakai, string $jamMulai, string $jamSelesai): bool
    {
        $namaHari = MstDay::URUTAN[Carbon::parse($tanggalPakai)->dayOfWeekIso - 1];

        // Dua rentang waktu bentrok hanya kalau benar-benar tumpang tindih, bukan
        // sekadar bersinggungan di satu titik (mis. jadwal 08:00-10:00 dan reservasi
        // 10:00-12:00 TIDAK bentrok karena hanya bersambung di jam 10:00).
        return static::where('id_lab', $idLab)
            ->whereHas('hari', fn ($q) => $q->where('nama_hari', $namaHari))
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();
    }
}
