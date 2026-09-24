<?php
namespace App\Imports;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class BarangMasukImport implements ToCollection, WithHeadingRow {
    private $operatorId;
    private $imported=0;
    private $errors=[];
    public function __construct($opId){ $this->operatorId=$opId; }
    public function collection(Collection $rows){
        foreach($rows as $i=>$row){
            $idx=$i+2;
            try{
                $nama=trim($row['nama_barang'] ?? $row['nama'] ?? '');
                $jumlah=(int)($row['jumlah'] ?? 0);
                $kategori=trim($row['kategori'] ?? 'Lainnya');
                $merk=trim($row['merk'] ?? '');
                $satuan=trim($row['satuan'] ?? 'pcs');
                $sumber=trim($row['sumber'] ?? 'Import Excel');
                if(empty($nama)){ $this->errors[]="Baris {$idx}: Nama barang wajib diisi"; continue; }
                if($jumlah<=0){ $this->errors[]="Baris {$idx}: Jumlah harus >0"; continue; }
                $barang=Barang::firstOrCreate(['nama'=>$nama,'kategori'=>$kategori], ['merk'=>$merk,'satuan'=>$satuan,'stok'=>0]);
                BarangMasuk::create(['barang_id'=>$barang->id,'jumlah'=>$jumlah,'sumber'=>$sumber,'catatan'=>$row['catatan']??null,'operator_id'=>$this->operatorId]);
                $barang->increment('stok',$jumlah);
                $this->imported++;
            }catch(\Exception $e){ $this->errors[]="Baris {$idx}: ".$e->getMessage(); }
        }
        if($this->imported>0) AuditLog::record($this->operatorId,'import_excel',"Import Excel: {$this->imported} barang masuk");
    }
    public function getImportedCount(){ return $this->imported; }
    public function getErrors(){ return $this->errors; }
}
