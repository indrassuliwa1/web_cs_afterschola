<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin CS',
            'email' => 'regiridwan220@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Supervisor',
            'email' => 'regiridwan2207@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'supervisor',
        ]);
    }
}
