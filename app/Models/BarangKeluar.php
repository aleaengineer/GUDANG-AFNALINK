<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BarangKeluar extends Model {
    protected $fillable=['barang_id','jumlah','teknisi_nama','teknisi_jabatan','serial_number','keperluan','operator_id'];
    public function barang(){ return $this->belongsTo(Barang::class); }
    public function operator(){ return $this->belongsTo(User::class,'operator_id'); }
}
