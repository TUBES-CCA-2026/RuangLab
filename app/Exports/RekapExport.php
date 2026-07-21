<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapExport implements FromArray, WithColumnWidths, WithEvents, WithTitle
{
    private const HEADER_FILL = 'DCE6F1';
    private const BORDER_COLOR = 'D9D9D9';

    public function __construct(
        private array $data,
        private array $filter,
    ) {
    }

    public function array(): array
    {
        return [
            ['Rekap Penggunaan Laboratorium', ''],
            ['Dicetak pada ' . now()->translatedFormat('d F Y H:i'), ''],
            ['', ''],
            ['Tahun Ajaran', $this->filter['tahun_ajaran']],
            ['Laboratorium', $this->filter['laboratorium']],
            ['Mata Kuliah', $this->filter['mata_kuliah']],
            ['', ''],
            ['Metrik', 'Nilai'],
            ['Jumlah Praktikum', $this->data['jumlah_praktikum']],
            ['Jumlah Penggunaan Laboratorium', $this->data['jumlah_penggunaan_lab']],
            ['Jumlah Reservasi', $this->data['jumlah_reservasi']],
            ['Jumlah Pembatalan', $this->data['jumlah_pembatalan']],
            ['Jam Penggunaan Laboratorium', $this->data['jam_penggunaan_lab']],
        ];
    }

    public function title(): string
    {
        return 'Rekap';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 34,
            'B' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul (baris 1): merge A1:B1, besar & bold.
                $sheet->mergeCells('A1:B1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Baris "Dicetak pada ..." (baris 2): merge, abu-abu, italic.
                $sheet->mergeCells('A2:B2');
                $sheet->getStyle('A2')->getFont()->setItalic(true)->getColor()->setRGB('6B7280');

                // Blok info filter (baris 4-6): label kolom A bold + border + fill halus.
                $sheet->getStyle('A4:A6')->getFont()->setBold(true);
                $sheet->getStyle('A4:B6')
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color(self::BORDER_COLOR));
                $sheet->getStyle('A4:B6')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F9FAFB');

                // Header tabel metrik (baris 8): bold + fill biru muda.
                $sheet->getStyle('A8:B8')->getFont()->setBold(true);
                $sheet->getStyle('A8:B8')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(self::HEADER_FILL);

                // Seluruh tabel metrik (baris 8-13): border tipis.
                $sheet->getStyle('A8:B13')
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color(self::BORDER_COLOR));

                // Kolom nilai (B9:B13): rata kanan, bold, format angka/jam.
                $sheet->getStyle('B9:B13')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('B9:B13')->getFont()->setBold(true);
                $sheet->getStyle('B13')->getNumberFormat()->setFormatCode('0.0 "jam"');

                $sheet->getRowDimension(1)->setRowHeight(22);
            },
        ];
    }
}