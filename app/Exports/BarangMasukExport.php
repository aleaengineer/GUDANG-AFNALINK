<?php
namespace App\Exports;
use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class BarangMasukExport implements FromCollection, WithHeadings, WithMapping {
    public function collection(){ return BarangMasuk::with(['barang','operator'])->latest()->get(); }
    public function headings(): array { return ['Tanggal','Barang','Kategori','Jumlah','Satuan','Sumber','Operator','Catatan']; }
    public function map($r): array { return [$r->created_at->format('d/m/Y H:i'),$r->barang->nama??'-',$r->barang->kategori??'-',$r->jumlah,$r->barang->satuan??'pcs',$r->sumber,$r->operator->name??'-',$r->catatan]; }
}
