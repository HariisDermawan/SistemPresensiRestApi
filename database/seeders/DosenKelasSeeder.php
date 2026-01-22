<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DosenKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosen1 = Dosen::where('nidn', '10001')->first();
        $dosen2 = Dosen::where('nidn', '10002')->first();

        $ti01 = Kelas::where('kode_kelas', 'TI-01')->first();
        $ti02 = Kelas::where('kode_kelas', 'TI-02')->first();

        $dosen1->kelas()->attach([$ti01->id, $ti02->id]);
        $dosen2->kelas()->attach([$ti01->id]);
    }
}
