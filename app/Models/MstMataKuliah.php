<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstMataKuliah extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'mst_mata_kuliah';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['nama_dosen', 'nama_matkul', 'sks', 'frekuensi', 'catatan_admin'];

    public function jadwal()
    {
        return $this->hasMany(TrxJadwalKuliah::class, 'id_matkul');
    }
}
