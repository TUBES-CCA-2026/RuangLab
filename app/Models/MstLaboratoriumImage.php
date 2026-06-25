<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstLaboratoriumImage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mst_laboratorium_images';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_laboratorium', 'image_path', 'urutan'];

    public function laboratorium()
    {
        return $this->belongsTo(MstLaboratorium::class, 'id_laboratorium');
    }
}
