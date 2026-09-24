<?php
namespace App\Exports;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class LaporanExport implements FromCollection, WithHeadings {
    private $from,$to,$bulan;
    public function __construct($from=null,$to=null,$bulan=null){ $this->from=$from; $this->to=$to; $this->bulan=$bulan; }
    public function collection(){
        $from=$this->from ? $this->from.' 00:00:00' : null;
        $to=$this->to ? $this->to.' 23:59:59' : null;
        $bulan=$this->bulan;
        $masuksQuery=BarangMasuk::with(['barang','operator']);
        $keluarsQuery=BarangKeluar::with(['barang','operator']);
        if($bulan){
            $year=substr($bulan,0,4); $month=substr($bulan,5,2);
            $masuksQuery->whereYear('created_at',$year)->whereMonth('created_at',$month);
            $keluarsQuery->whereYear('created_at',$year)->whereMonth('created_at',$month);
        } else {
            $masuksQuery->when($from, fn($q)=>$q->where('created_at','>=',$from))->when($to, fn($q)=>$q->where('created_at','<=',$to));
            $keluarsQuery->when($from, fn($q)=>$q->where('created_at','>=',$from))->when($to, fn($q)=>$q->where('created_at','<=',$to));
        }
        $masuks=$masuksQuery->get()->map(fn($r)=>['Tanggal'=>$r->created_at->format('d/m/Y H:i'),'Tipe'=>'MASUK','Barang'=>$r->barang->nama??'-','Jumlah'=>$r->jumlah,'Teknisi/Sumber'=>$r->sumber??'-','Jabatan'=>'-','Operator'=>$r->operator->name??'-']);
        $keluars=$keluarsQuery->get()->map(fn($r)=>['Tanggal'=>$r->created_at->format('d/m/Y H:i'),'Tipe'=>'KELUAR','Barang'=>$r->barang->nama??'-','Jumlah'=>$r->jumlah,'Teknisi/Sumber'=>$r->teknisi_nama,'Jabatan'=>$r->teknisi_jabatan,'Operator'=>$r->operator->name??'-']);
        return $masuks->merge($keluars)->sortBy('Tanggal');
    }
    public function headings(): array { return ['Tanggal','Tipe','Barang','Jumlah','Teknisi/Sumber','Jabatan','Operator']; }
}
