<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxJadwalKuliah extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'trx_jadwal_kuliah';
    use HasFactory;
    protected $fillable = ['id_matkul', 'id_lab', 'id_day', 'jam_mulai', 'jam_selesai'];
}
