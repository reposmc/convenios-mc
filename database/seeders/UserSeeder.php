<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::findOrFail(1);
        $roleUser = Role::findOrFail(2);

        $user = User::create([
            'id' => 1,
            'name' => 'Administrador',
            'last_name' => 'Administrador',
            // 'dui' => '02475605-7',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole($roleAdmin);

        $user = User::create([
            'id' => 2,
            'name' => 'Usuario',
            'last_name' => 'Usuario',
            // 'dui' => '02475605-1',
            'email' => 'usuario@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole($roleUser);
    }
}
