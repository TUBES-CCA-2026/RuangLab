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
}
