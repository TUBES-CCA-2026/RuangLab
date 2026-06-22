<?php

namespace App\Exports;

use App\Models\TrxReservasi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservasiExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = TrxReservasi::with(['user', 'detail.laboratorium']);

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['dari'])) {
            $query->whereDate('tanggal_pengajuan', '>=', $this->filters['dari']);
        }

        if (!empty($this->filters['sampai'])) {
            $query->whereDate('tanggal_pengajuan', '<=', $this->filters['sampai']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Kode Reservasi', 'Nama User', 'Email', 'Keperluan',
            'Ruangan', 'Tanggal Pakai', 'Jam Mulai', 'Jam Selesai',
            'Status', 'Tanggal Pengajuan', 'Catatan Admin',
        ];
    }

    public function map($row): array
    {
        $detail = $row->detail->first();
        return [
            $row->kode_reservasi,
            $row->user->nama ?? '-',
            $row->user->email ?? '-',
            $row->keperluan,
            $detail->laboratorium->nama_lab ?? '-',
            $detail->tanggal_pakai ?? '-',
            $detail->jam_mulai ?? '-',
            $detail->jam_selesai ?? '-',
            $row->status,
            $row->tanggal_pengajuan,
            $row->catatan_admin ?? '-',
        ];
    }
}