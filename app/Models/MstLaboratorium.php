<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstLaboratorium extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mst_laboratorium';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['nama_lab', 'penanggung_jawab', 'image', 'status', 'fasilitas', 'kapasitas'];

    public function penanggungJawab()
    {
        return $this->belongsTo(MstUser::class, 'penanggung_jawab');
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(TrxJadwalKuliah::class, 'id_lab');
    }

    public function detailReservasi()
    {
        return $this->hasMany(TrxDetailReservasi::class, 'id_ruangan');
    }

    public function images()
    {
        return $this->hasMany(MstLaboratoriumImage::class, 'id_laboratorium')->orderBy('urutan');
    }
}
