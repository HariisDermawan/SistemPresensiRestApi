<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen_Kelas extends Model
{
    protected $table = 'dosen_kelas';

    protected $fillable = [
        'dosen_id',
        'kelas_id'
    ];
}
