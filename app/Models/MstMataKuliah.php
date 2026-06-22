<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstMataKuliah extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mst_mata_kuliah';
    protected $fillable = ['nama_dosen', 'nama_matkul', 'sks', 'frekuensi', 'catatan_admin'];
    use HasFactory;
}

