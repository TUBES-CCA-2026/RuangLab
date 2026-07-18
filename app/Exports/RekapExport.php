<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(
        private array $data,
        private array $filter,
    ) {
    }

    public function array(): array
    {
        return [
            ['Tahun Ajaran', $this->filter['tahun_ajaran']],
            ['Laboratorium', $this->filter['laboratorium']],
            ['Mata Kuliah', $this->filter['mata_kuliah']],
            ['', ''],
            ['Jumlah Praktikum', $this->data['jumlah_praktikum']],
            ['Jumlah Penggunaan Laboratorium', $this->data['jumlah_penggunaan_lab']],
            ['Jumlah Reservasi', $this->data['jumlah_reservasi']],
            ['Jumlah Pembatalan', $this->data['jumlah_pembatalan']],
            ['Jam Penggunaan Laboratorium', $this->data['jam_penggunaan_lab']],
        ];
    }

    public function headings(): array
    {
        return ['Rekap Penggunaan Laboratorium', ''];
    }

    public function title(): string
    {
        return 'Rekap';
    }
}
