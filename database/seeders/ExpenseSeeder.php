<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $group = $users->first()->group;

        foreach ($users as $user) {
            // Create shared expenses
            Expense::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'amount' => 1000,
                'type' => 'rent',
                'description' => 'Monthly Rent',
                'date' => now(),
                'is_shared' => true,
            ]);

            Expense::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'amount' => 200,
                'type' => 'utilities',
                'description' => 'Electricity Bill',
                'date' => now(),
                'is_shared' => true,
            ]);

            // Create personal expenses
            Expense::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'amount' => 50,
                'type' => 'other',
                'description' => 'Personal Expense',
                'date' => now(),
                'is_shared' => false,
            ]);
        }
    }
}
