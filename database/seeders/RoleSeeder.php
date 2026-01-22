<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            'manage_dosen',
            'manage_siswa',
            'manage_kelas',
            'view_all_presensi',
            'input_presensi',
            'edit_presensi',
            'delete_presensi',
            'view_own_presensi',
            'view_own_profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $dosen = Role::firstOrCreate([
            'name' => 'dosen',
            'guard_name' => 'web',
        ]);

        $siswa = Role::firstOrCreate([
            'name' => 'siswa',
            'guard_name' => 'web',
        ]);
        $admin->syncPermissions([
            'manage_dosen',
            'manage_siswa',
            'manage_kelas',
            'view_all_presensi',
        ]);

        $dosen->syncPermissions([
            'input_presensi',
            'edit_presensi',
            'delete_presensi',
            'view_all_presensi',
        ]);

        $siswa->syncPermissions([
            'view_own_presensi',
            'view_own_profile',
        ]);
    }
}
