<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class HistoryReservasiExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private Collection $reservasis)
    {
    }

    public function collection(): Collection
    {
        return $this->reservasis;
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Peminjam', 'Email', 'Laboratorium', 'Mata Kuliah',
            'Tanggal Pakai', 'Jam Mulai', 'Jam Selesai', 'Keperluan',
            'Status', 'Tanggal Pengajuan', 'Prioritas',
        ];
    }

    public function map($r): array
    {
        static $nomor = 0;
        $nomor++;

        $detail = $r->detail->first();

        return [
            $nomor,
            $r->user->nama ?? '-',
            $r->user->email ?? '-',
            $detail->laboratorium->nama_lab ?? '-',
            $detail->mataKuliah->nama_matkul ?? '-',
            $detail ? \Carbon\Carbon::parse($detail->tanggal_pakai)->format('d/m/Y') : '-',
            $detail ? substr($detail->jam_mulai, 0, 5) : '-',
            $detail ? substr($detail->jam_selesai, 0, 5) : '-',
            $r->keperluan,
            ucwords(str_replace('_', ' ', $r->status)),
            \Carbon\Carbon::parse($r->tanggal_pengajuan)->format('d/m/Y'),
            $r->is_prioritas ? 'Ya' : 'Tidak',
        ];
    }

    public function title(): string
    {
        return 'History Reservasi';
    }
}
