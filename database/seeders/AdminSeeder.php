<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $create_user = Permission::create(['name' => 'create_user']);
        $edit_user = Permission::create(['name' => 'edit_user']);
        $read_user = Permission::create(['name' => 'read_user']);
        $delete_user = Permission::create(['name' => 'delete_user']);

        $create_absen = Permission::create(['name' => 'create_absen']);
        $read_absen = Permission::create(['name' => 'read_absen']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo($create_user,$read_user,$edit_user,$delete_user,$read_absen);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo($create_absen,$read_absen,$read_user);

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole('admin');
    }
}
