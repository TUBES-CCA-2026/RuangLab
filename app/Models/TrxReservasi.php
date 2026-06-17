<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxReservasi extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'trx_reservasi';
    protected $fillable = ['id_user', 'kode_reservasi', 'kode_checkin', 'batas_checkin', 'waktu_checkin', 'tanggal_pengajuan', 'keperluan', 'status', 'catatan_admin'];
    use HasFactory;
}
