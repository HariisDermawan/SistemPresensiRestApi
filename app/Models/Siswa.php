<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswas'; 
    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'email',
        'kelas_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }

    public function presensis(){
        return $this->hasMany(Presensi::class);
    }

    
}
