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
<<<<<<< HEAD
}
=======
    
    public function reservasi()
    {
        return $this->hasMany(TrxReservasi::class, 'id_user');
    }

    public function laboratoriumDikelola()
    {
        return $this->hasMany(MstLaboratorium::class, 'penanggung_jawab');
    }

    public function isAdmin()
{
    return $this->role->nama_role === 'laboran'; 
}
    
    public function isAslab()
    {
        return $this->role && strtolower($this->role->nama_role) === 'aslab';
    }
}
>>>>>>> 337a13c (feat: tambah fitur history reservasi role aslab dan perbaikan sidebar aslab)
