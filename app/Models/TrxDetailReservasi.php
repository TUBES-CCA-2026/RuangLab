<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxDetailReservasi extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'trx_detail_reservasi';
    protected $fillable = ['id_reservasi', 'id_ruangan', 'tanggal_pakai', 'jam_mulai', 'jam_selesai'];
    use HasFactory;
}
