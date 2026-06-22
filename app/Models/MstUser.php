<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\MstRole;

class MstUser extends Model
{
    use HasUuids;

    protected $table = 'mst_users';

    protected $keyType = 'string';
    public $incrementing = false;

 protected $fillable = ['nama', 'email', 'password', 'no_telp', 'id_role', 'status'];

    // relasi ke role
    public function role()
    {
        return $this->belongsTo(MstRole::class, 'role_id');
    }
}