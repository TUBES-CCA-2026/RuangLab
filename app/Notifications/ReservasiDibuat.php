<?php

namespace App\Notifications;

use App\Models\TrxReservasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservasiDibuat extends Notification
{
    use Queueable;

    public function __construct(public TrxReservasi $reservasi) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $detail  = $this->reservasi->detail->first();
        $labNama = $detail?->laboratorium?->nama_lab ?? '-';
        $tgl     = $detail?->tanggal_pakai
            ? \Carbon\Carbon::parse($detail->tanggal_pakai)->translatedFormat('d M Y')
            : '-';

        return [
            'reservasi_id' => $this->reservasi->id,
            'status'       => 'pending',
            'pesan'        => "Reservasi baru dari {$this->reservasi->user->nama} untuk lab {$labNama} pada {$tgl} menunggu persetujuan.",
        ];
    }
}