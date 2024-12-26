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
            'name' => 'Lemon',
            'email' => 'leilou.guimond@orange.fr',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => "P'tit Lu",
            'email' => 'lucas.beyer@gmx.fr',
            'password' => Hash::make('password'),
        ]);
    }
}
