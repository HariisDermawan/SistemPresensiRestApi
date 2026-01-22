<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Siswa;
use App\Models\Presensi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $dosen = Dosen::first();
        $siswas = Siswa::all();

        foreach ($siswas as $siswa) {
            Presensi::create([
                'tanggal' => now()->subDays(1),
                'siswa_id' => $siswa->id,
                'dosen_id' => $dosen->id,
                'status' => 'Hadir',
                'keterangan' => null,
            ]);
        }
    }
}
