<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'kode_kelas',
        'nama_kelas',
        'jurusan'
    ];

    public function siswas(){
        return $this->hasMany(Siswa::class);
    }

    public function dosens(){
        return $this->belongsToMany(Dosen::class, 'dosen_kelas');
    }
}
