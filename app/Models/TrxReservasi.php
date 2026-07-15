<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxReservasi extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'trx_reservasi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_user', 'kode_checkin', 'batas_checkin',
        'waktu_checkin', 'tanggal_pengajuan', 'keperluan', 'status', 'catatan_admin',
        'is_prioritas', 'checked_in_at', 'checked_out_at',
    ];

    protected $casts = [
        'is_prioritas' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(MstUser::class, 'id_user');
    }

    public function detail()
    {
        return $this->hasMany(TrxDetailReservasi::class, 'id_reservasi');
    }

    /**
     * Tandai reservasi "sedang_dipakai" yang jadwal pakainya sudah lewat sebagai "selesai".
     */
    public static function autoCompleteExpired(): void
    {
        static::where('status', 'sedang_dipakai')
            ->whereHas('detail', function ($q) {
                $q->where('tanggal_pakai', '<', now()->toDateString())
                    ->orWhere(function ($qq) {
                        $qq->where('tanggal_pakai', now()->toDateString())
                            ->where('jam_selesai', '<', now()->format('H:i:s'));
                    });
            })
            ->update(['status' => 'selesai']);
    }
}
