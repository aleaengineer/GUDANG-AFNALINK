<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PegawaiTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Budi Santoso', 'Teknisi', '081234567801', 'budi@afnalink.my.id', 'aktif'],
            ['Siti Rahayu', 'NOC', '081234567802', 'siti@afnalink.my.id', 'aktif'],
            ['Rina Marlina', 'Marketing', '081234567803', 'rina@afnalink.my.id', 'aktif'],
            ['Dewi Kasir', 'Kasir', '081234567804', 'dewi@afnalink.my.id', 'aktif'],
            ['Pak Direktur', 'CEO', '081234567805', 'ceo@afnalink.my.id', 'aktif'],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'jabatan', 'no_hp', 'email', 'status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '6366F1']]],
        ];
    }
}
