<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Karyawan::orderBy('jabatan')->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Jabatan', 'No HP', 'Email', 'Alamat', 'Status'];
    }

    public function map($k): array
    {
        return [$k->nama, $k->jabatan, $k->no_hp, $k->email, $k->alamat, $k->status];
    }
}
