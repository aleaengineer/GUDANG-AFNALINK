<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AuditLog extends Model {
    protected $fillable=['user_id','aksi','deskripsi'];
    public function user(){ return $this->belongsTo(User::class); }
    public static function record($userId,$aksi,$deskripsi){ self::create(['user_id'=>$userId,'aksi'=>$aksi,'deskripsi'=>$deskripsi]); }
}
