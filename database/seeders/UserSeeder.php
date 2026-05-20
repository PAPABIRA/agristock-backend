<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@agristock.sn',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Mamadou Diop',
            'email'    => 'producteur@agristock.sn',
            'password' => Hash::make('password'),
            'role'     => 'producteur',
        ]);

        User::create([
            'name'     => 'Ibrahima Sall',
            'email'    => 'client@agristock.sn',
            'password' => Hash::make('password'),
            'role'     => 'client',
        ]);
    }
}

