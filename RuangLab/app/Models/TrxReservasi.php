<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxReservasi extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'trx_reservasi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_user', 'kode_reservasi', 'kode_checkin', 'batas_checkin',
        'waktu_checkin', 'tanggal_pengajuan', 'keperluan', 'status', 'catatan_admin'
    ];

    public function user()
    {
        return $this->belongsTo(MstUser::class, 'id_user');
    }

    public function detail()
    {
        return $this->hasMany(TrxDetailReservasi::class, 'id_reservasi');
    }
}
