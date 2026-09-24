<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Barang extends Model {
    protected $fillable=['nama','kategori','merk','spesifikasi','serial_number','stok','satuan','foto','deletion_status','deletion_requested_by','deletion_requested_at','deletion_reviewed_by','deletion_reviewed_at'];
    protected $casts=['deletion_requested_at'=>'datetime','deletion_reviewed_at'=>'datetime'];
    public function masuks(){ return $this->hasMany(BarangMasuk::class); }
    public function keluars(){ return $this->hasMany(BarangKeluar::class); }
    public function deletionRequester(){ return $this->belongsTo(User::class,'deletion_requested_by'); }
    public function scopeSearch($q,$s){ if($s) $q->where(fn($qq)=>$qq->where('nama','like',"%{$s}%")->orWhere('kategori','like',"%{$s}%")->orWhere('merk','like',"%{$s}%")); return $q; }
    public function scopeLowStock($q,$t=5){ return $q->where('stok','<=',$t); }
    public function scopePending($q){ return $q->where('deletion_status','pending'); }
    public function getIsLowStockAttribute(){ return $this->stok <= 5; }
    public function isPending(){ return $this->deletion_status==='pending'; }
}
