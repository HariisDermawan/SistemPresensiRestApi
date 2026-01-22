<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kelas::insert([
            [
                'kode_kelas' => 'TI-01',
                'nama_kelas' => 'Teknik Informatika 1',
                'jurusan' => 'Teknik Informatika',
            ],
            [
                'kode_kelas' => 'TI-02',
                'nama_kelas' => 'Teknik Informatika 2',
                'jurusan' => 'Teknik Informatika',
            ],
            [
                'kode_kelas' => 'SI-01',
                'nama_kelas' => 'Sistem Informasi 1',
                'jurusan' => 'Sistem Informasi',
            ],
        ]);
    }
}
