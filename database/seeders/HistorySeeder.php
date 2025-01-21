<?php

namespace Database\Seeders;

use App\Models\History;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $group = $users->first()->group;

        foreach ($users as $user) {
            // Create history for last month
            History::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'month_year' => Carbon::now()->subMonth(),
                'data' => [
                    'expenses' => [
                        [
                            'description' => 'Monthly Rent',
                            'amount' => 1000,
                            'type' => 'rent',
                            'is_shared' => true,
                        ],
                        [
                            'description' => 'Electricity Bill',
                            'amount' => 200,
                            'type' => 'utilities',
                            'is_shared' => true,
                        ],
                        [
                            'description' => 'Personal Expense',
                            'amount' => 50,
                            'type' => 'other',
                            'is_shared' => false,
                        ],
                    ],
                    'incomes' => [
                        [
                            'description' => 'Monthly Salary',
                            'amount' => 2000,
                            'type' => 'salary',
                            'is_shared' => true,
                        ],
                        [
                            'description' => 'Personal Income',
                            'amount' => 100,
                            'type' => 'other',
                            'is_shared' => false,
                        ],
                    ],
                ],
            ]);

            // Create history for two months ago
            History::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'month_year' => Carbon::now()->subMonths(2),
                'data' => [
                    'expenses' => [
                        [
                            'description' => 'Monthly Rent',
                            'amount' => 1000,
                            'type' => 'rent',
                            'is_shared' => true,
                        ],
                        [
                            'description' => 'Electricity Bill',
                            'amount' => 180,
                            'type' => 'utilities',
                            'is_shared' => true,
                        ],
                        [
                            'description' => 'Personal Expense',
                            'amount' => 45,
                            'type' => 'other',
                            'is_shared' => false,
                        ],
                    ],
                    'incomes' => [
                        [
                            'description' => 'Monthly Salary',
                            'amount' => 2000,
                            'type' => 'salary',
                            'is_shared' => true,
                        ],
                        [
                            'description' => 'Personal Income',
                            'amount' => 90,
                            'type' => 'other',
                            'is_shared' => false,
                        ],
                    ],
                ],
            ]);
        }
    }
}
