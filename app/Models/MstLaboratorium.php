<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstLaboratorium extends Model
{
    
    protected $table = 'mst_laboratorium';
    use HasFactory;
    protected $fillable = ['nama_lab', 'penanggung_jawab', 'image', 'status', 'fasilitas', 'kapasitas'];
}
