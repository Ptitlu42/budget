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
        $lemon = User::where('email', 'leilou.guimond@orange.fr')->first();
        $ptitlu = User::where('email', 'lucas.beyer@gmx.fr')->first();

        for ($i = 12; $i > 0; $i--) {
            $date = Carbon::now()->subMonths($i)->startOfMonth();

            $lemonIncomes = [
                [
                    'description' => 'Medhi Salary',
                    'amount' => rand(650, 750),
                    'type' => 'salary',
                    'date' => $date,
                    'user_id' => $lemon->id,
                ],
                [
                    'description' => 'Center Salary',
                    'amount' => rand(350, 450),
                    'type' => 'salary',
                    'date' => $date,
                    'user_id' => $lemon->id,
                ]
            ];

            $ptitluIncomes = [
                [
                    'description' => 'Nicely Salary',
                    'amount' => rand(1650, 1750),
                    'type' => 'salary',
                    'date' => $date,
                    'user_id' => $ptitlu->id,
                ],
                [
                    'description' => 'Activity Bonus',
                    'amount' => 40,
                    'type' => 'aid',
                    'date' => $date,
                    'user_id' => $ptitlu->id,
                ]
            ];

            $expenses = [
                [
                    'description' => 'Rent',
                    'amount' => 580,
                    'type' => 'rent',
                    'date' => $date,
                    'is_shared' => true,
                ],
                [
                    'description' => 'Electricity',
                    'amount' => rand(100, 140),
                    'type' => 'utilities',
                    'date' => $date,
                    'is_shared' => true,
                ],
                [
                    'description' => 'Water',
                    'amount' => rand(18, 22),
                    'type' => 'utilities',
                    'date' => $date,
                    'is_shared' => true,
                ],
                [
                    'description' => 'Health Insurance',
                    'amount' => 50,
                    'type' => 'insurance',
                    'date' => $date,
                    'is_shared' => true,
                ],
                [
                    'description' => 'Home Insurance',
                    'amount' => 15,
                    'type' => 'insurance',
                    'date' => $date,
                    'is_shared' => true,
                ]
            ];

            for ($j = 0; $j < rand(3, 5); $j++) {
                $expenses[] = [
                    'description' => 'Groceries ' . ($j + 1),
                    'amount' => rand(50, 150),
                    'type' => 'groceries',
                    'date' => $date->copy()->addDays(rand(1, 28)),
                    'is_shared' => true,
                ];
            }

            $total_incomes = collect($lemonIncomes)->sum('amount') + collect($ptitluIncomes)->sum('amount');
            $total_expenses = collect($expenses)->sum('amount');
            $total_shared_expenses = collect($expenses)->where('is_shared', true)->sum('amount');

            $shares = [
                [
                    'name' => $lemon->name,
                    'email' => $lemon->email,
                    'total_income' => collect($lemonIncomes)->sum('amount'),
                    'share_percentage' => (collect($lemonIncomes)->sum('amount') / $total_incomes) * 100
                ],
                [
                    'name' => $ptitlu->name,
                    'email' => $ptitlu->email,
                    'total_income' => collect($ptitluIncomes)->sum('amount'),
                    'share_percentage' => (collect($ptitluIncomes)->sum('amount') / $total_incomes) * 100
                ]
            ];

            History::create([
                'month_year' => $date,
                'incomes_data' => array_merge($lemonIncomes, $ptitluIncomes),
                'expenses_data' => $expenses,
                'total_incomes' => $total_incomes,
                'total_expenses' => $total_expenses,
                'total_shared_expenses' => $total_shared_expenses,
                'shares_data' => $shares
            ]);
        }
    }
}
