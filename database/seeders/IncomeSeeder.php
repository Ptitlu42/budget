<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $group = $users->first()->group;

        foreach ($users as $user) {
            // Create shared income
            Income::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'amount' => 2000,
                'type' => 'salary',
                'description' => 'Monthly Salary',
                'date' => now(),
                'is_shared' => true,
            ]);

            // Create personal income
            Income::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'amount' => 100,
                'type' => 'other',
                'description' => 'Personal Income',
                'date' => now(),
                'is_shared' => false,
            ]);
        }
    }
}
