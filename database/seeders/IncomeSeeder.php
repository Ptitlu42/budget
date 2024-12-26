<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $lemon = User::where('email', 'leilou.guimond@orange.fr')->first();
        $ptitlu = User::where('email', 'lucas.beyer@gmx.fr')->first();

        Income::create([
            'user_id' => $lemon->id,
            'description' => 'Salaire Medhi',
            'amount' => 700,
            'type' => 'salary',
            'date' => Carbon::now(),
        ]);

        Income::create([
            'user_id' => $lemon->id,
            'description' => 'Salaire Centre',
            'amount' => 400,
            'type' => 'salary',
            'date' => Carbon::now(),
        ]);

        Income::create([
            'user_id' => $ptitlu->id,
            'description' => 'Salaire Nicely',
            'amount' => 1700,
            'type' => 'salary',
            'date' => Carbon::now(),
        ]);

        Income::create([
            'user_id' => $ptitlu->id,
            'description' => "Prime d'activité",
            'amount' => 40,
            'type' => 'aid',
            'date' => Carbon::now(),
        ]);
    }
}
