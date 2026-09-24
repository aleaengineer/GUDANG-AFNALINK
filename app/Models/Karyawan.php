<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $fillable = ['nama', 'jabatan', 'no_hp', 'email', 'alamat', 'status'];

    public function scopeAktif($q)
    {
        return $q->where('status', 'aktif');
    }

    public function scopeByJabatan($q, $jabatan)
    {
        if ($jabatan) return $q->where('jabatan', $jabatan);
        return $q;
    }

    public function scopeSearch($q, $s)
    {
        if ($s) $q->where('nama', 'like', "%{$s}%");
        return $q;
    }
}
