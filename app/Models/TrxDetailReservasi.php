<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxDetailReservasi extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'trx_detail_reservasi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_reservasi', 'id_ruangan', 'id_matkul', 'tanggal_pakai', 'jam_mulai', 'jam_selesai'];

    public function reservasi()
    {
        return $this->belongsTo(TrxReservasi::class, 'id_reservasi');
    }

    public function laboratorium()
    {
        return $this->belongsTo(MstLaboratorium::class, 'id_ruangan');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MstMataKuliah::class, 'id_matkul');
    }
}
