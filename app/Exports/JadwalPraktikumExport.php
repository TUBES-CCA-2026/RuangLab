<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Kolom nama_matkul, nama_dosen, sks, hari, jam_mulai, jam_selesai, id_ruangan
 * sengaja sama persis dengan template Import Jadwal, supaya file hasil export
 * ini bisa langsung diimpor ulang tanpa perlu diedit dulu.
 */
class JadwalPraktikumExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private Collection $jadwals)
    {
    }

    public function collection(): Collection
    {
        return $this->jadwals;
    }

    public function headings(): array
    {
        return [
            'nama_matkul', 'nama_dosen', 'sks', 'hari', 'jam_mulai', 'jam_selesai', 'id_ruangan',
            'Laboratorium', 'Tahun Ajaran',
        ];
    }

    public function map($jd): array
    {
        return [
            $jd->mataKuliah->nama_matkul ?? '-',
            $jd->mataKuliah->nama_dosen ?? '-',
            $jd->mataKuliah->sks ?? '-',
            $jd->hari->nama_hari ?? '-',
            substr($jd->jam_mulai, 0, 5),
            substr($jd->jam_selesai, 0, 5),
            $jd->id_lab,
            $jd->laboratorium->nama_lab ?? '-',
            $jd->tahunAjaran->nama ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Jadwal Praktikum';
    }
}
