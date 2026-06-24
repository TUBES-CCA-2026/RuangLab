<?php

namespace App\Notifications;

use App\Models\TrxReservasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservasiStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public TrxReservasi $reservasi)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $detail = $this->reservasi->detail->first();
        $labNama = $detail?->laboratorium?->nama_lab ?? '-';

        $statusLabel = match ($this->reservasi->status) {
            'disetujui'      => 'disetujui',
            'ditolak'        => 'ditolak',
            'sedang_dipakai' => 'sedang dipakai',
            'hangus'         => 'hangus',
            default          => $this->reservasi->status,
        };

        return [
            'reservasi_id' => $this->reservasi->id,
            'status'       => $this->reservasi->status,
            'pesan'        => "Reservasi lab {$labNama} Anda telah {$statusLabel}.",
            'catatan'      => $this->reservasi->catatan_admin,
        ];
    }
}
