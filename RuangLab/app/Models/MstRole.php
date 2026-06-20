<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MstUser;

class MstRole extends Model
{
    use HasUuids;

    protected $table = 'mst_roles';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_role'
    ];

    public function users()
    {
        return $this->hasMany(MstUser::class, 'id_role');
    }
}
