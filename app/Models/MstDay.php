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

    public function jadwal()
    {
        return $this->hasMany(TrxJadwalKuliah::class, 'id_day');
    }
}
