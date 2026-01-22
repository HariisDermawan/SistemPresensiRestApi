<?php

namespace App\Models;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = [
        'user_id',
        'nidn',
        'nama',
        'email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI MANY TO MANY KE KELAS
    public function kelas()
    {
        return $this->belongsToMany(
            Kelas::class,  
            'dosen_kelas',             
            'dosen_id',                
            'kelas_id'                 
        );
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
