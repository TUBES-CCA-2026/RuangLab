<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstDay extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mst_day';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['nama_hari'];

    /**
     * Urutan hari Senin–Minggu, selaras dengan Carbon::dayOfWeekIso (1 = Senin ... 7 = Minggu).
     */
    public const URUTAN = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu', 'Minggu'];

    public function jadwal()
    {
        return $this->hasMany(TrxJadwalKuliah::class, 'id_day');
    }

    public static function namaHariIni(): string
    {
        return self::URUTAN[now()->dayOfWeekIso - 1];
    }
}
