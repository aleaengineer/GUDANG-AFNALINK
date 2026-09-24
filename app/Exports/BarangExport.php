<?php
namespace App\Exports;
use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class BarangExport implements FromCollection, WithHeadings, WithMapping {
    public function collection(){ return Barang::orderBy('nama')->get(); }
    public function headings(): array { return ['Nama','Kategori','Merk','Stok','Satuan','Serial Number','Spesifikasi']; }
    public function map($b): array { return [$b->nama,$b->kategori,$b->merk,$b->stok,$b->satuan,$b->serial_number,$b->spesifikasi]; }
}
