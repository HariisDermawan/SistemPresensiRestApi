<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $fillable = [
        'tanggal',
        'siswa_id',
        'dosen_id',
        'status',
        'keterangan'
    ];


    public function siswa(){
        return $this->belongsTo(Siswa::class);
    }

    public function dosen(){
        return $this->belongsTo(Dosen::class);
    }
}
