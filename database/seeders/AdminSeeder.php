<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'kampusku@gmail.com',
            'password' => bcrypt('admin123'),
        ]);
    }
}
