<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $dosens = [
            [
                'nidn'  => '10001',
                'nama'  => 'Haris Dar, S.Kom',
                'email' => 'haris@kampus.com',
            ],
            [
                'nidn'  => '10002',
                'nama'  => 'Pratiwi Aprilia Rahmawati, S.H',
                'email' => 'pratiwi@kampus.com',
            ],
        ];

        foreach ($dosens as $data) {
            $user = User::firstOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'name'     => $data['nama'],   
                    'email'    => $data['email'],
                    'password' => Hash::make('password'),
                ]
            );

            if (!$user->hasRole('dosen')) {
                $user->assignRole('dosen');
            }

            Dosen::firstOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'user_id' => $user->id,
                    'nama'    => $data['nama'],
                    'email'   => $data['email'],
                ]
            );
        }
    }
}
