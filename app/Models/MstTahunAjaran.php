<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif'        => 'boolean',
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

    /**
     * Jadikan tahun ajaran ini satu-satunya yang aktif.
     */
    public function jadikanAktif(): void
    {
        DB::transaction(function () {
            static::where('id', '!=', $this->id)->update(['is_aktif' => false]);
            $this->update(['is_aktif' => true]);
        });
    }
}
