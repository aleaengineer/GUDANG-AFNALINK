<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BarangMasuk extends Model {
    protected $fillable=['barang_id','jumlah','sumber','catatan','operator_id'];
    public function barang(){ return $this->belongsTo(Barang::class); }
    public function operator(){ return $this->belongsTo(User::class,'operator_id'); }
}
