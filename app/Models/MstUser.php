<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MstUser extends Authenticatable
{
    use HasUuids, HasFactory, Notifiable;

    protected $table = 'mst_users';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['nama', 'email', 'password', 'no_telp', 'id_role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    // Laravel auth expects "name" attribute by default in many places; map nama -> name
    public function getNameAttribute()
    {
        return $this->nama;
    }

    public function role()
    {
        return $this->belongsTo(MstRole::class, 'id_role');
    }

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
        return $this->role && strtolower($this->role->nama_role) === 'laboran';
    }

    public function isAslab()
    {
        return $this->role && strtolower($this->role->nama_role) === 'aslab';
    }

    public function isPeminjam()
    {
        return $this->role && strtolower($this->role->nama_role) === 'peminjam';
    }
}
