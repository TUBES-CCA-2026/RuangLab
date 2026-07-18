<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstTahunAjaran extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'mst_tahun_ajaran';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama', 'semester', 'tahun_ajaran', 'tanggal_mulai', 'tanggal_selesai', 'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function jadwalKuliah()
    {
        return $this->hasMany(TrxJadwalKuliah::class, 'id_tahun_ajaran');
    }

    public function reservasi()
    {
        return $this->hasMany(TrxReservasi::class, 'id_tahun_ajaran');
    }

    public static function aktif(): ?self
    {
        return static::where('is_aktif', true)->first();
    }
}
