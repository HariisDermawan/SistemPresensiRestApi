<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kelasTI01 = Kelas::where('kode_kelas', 'TI-01')->first();
        $kelasTI02 = Kelas::where('kode_kelas', 'TI-02')->first();

        $siswas = [
            [
                'nim'   => '220001',
                'nama'  => 'Andi Wijaya',
                'email' => 'andi@test.com',
                'kelas' => $kelasTI01,
            ],
            [
                'nim'   => '220002',
                'nama'  => 'Rina Putri',
                'email' => 'rina@test.com',
                'kelas' => $kelasTI01,
            ],
            [
                'nim'   => '220003',
                'nama'  => 'Doni Saputra',
                'email' => 'doni@test.com',
                'kelas' => $kelasTI02,
            ],
        ];

        foreach ($siswas as $data) {

            // === USER ===
            $user = User::firstOrCreate(
                ['nim' => $data['nim']],
                [
                    'name'     => $data['nama'],   // 🔥 WAJIB
                    'email'    => $data['email'],
                    'password' => Hash::make('password'),
                ]
            );

            if (!$user->hasRole('siswa')) {
                $user->assignRole('siswa');
            }

            // === SISWA ===
            Siswa::firstOrCreate(
                ['nim' => $data['nim']],
                [
                    'user_id'  => $user->id,
                    'nama'     => $data['nama'],
                    'email'    => $data['email'],
                    'kelas_id'=> $data['kelas']->id,
                ]
            );
        }
    }
}
