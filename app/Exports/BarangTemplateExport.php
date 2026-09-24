<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Router TP-Link TL-WR840N', 'Router', 'TP-Link', 10, 'pcs', 'SN-001', 'Router 300Mbps untuk pelanggan'],
            ['ONT Huawei HG8245H', 'ONT', 'Huawei', 15, 'pcs', 'SN-002', 'ONT Fiber untuk FTTH'],
            ['Kabel FO Dropcore 100m', 'Kabel FO', 'Generic', 20, 'roll', '', 'Kabel FO dropcore'],
            ['Splitter 1:8', 'Splitter', 'Generic', 5, 'pcs', '', 'Splitter untuk ODP'],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'kategori', 'merk', 'stok', 'satuan', 'serial_number', 'spesifikasi'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '22D3EE']]],
        ];
    }
}
