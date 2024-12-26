<?php

namespace Database\Factories;

use App\Models\History;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition(): array
    {
        $user1 = User::factory()->create([
            'name' => 'Lemon',
            'email' => 'leilou.guimond@orange.fr',
        ]);
        $user2 = User::factory()->create([
            'name' => "P'tit Lu",
            'email' => 'lucas.beyer@gmx.fr',
        ]);

        $incomesData = [
            [
                'description' => 'Salary 1',
                'amount' => $this->faker->randomFloat(2, 500, 1000),
                'type' => 'salary',
                'date' => Carbon::now(),
                'user_id' => $user1->id,
            ],
            [
                'description' => 'Salary 2',
                'amount' => $this->faker->randomFloat(2, 1500, 2000),
                'type' => 'salary',
                'date' => Carbon::now(),
                'user_id' => $user2->id,
            ],
        ];

        $expensesData = [
            [
                'description' => 'Rent',
                'amount' => 580,
                'type' => 'rent',
                'date' => Carbon::now(),
                'is_shared' => true,
            ],
            [
                'description' => 'Utilities',
                'amount' => $this->faker->randomFloat(2, 100, 200),
                'type' => 'utilities',
                'date' => Carbon::now(),
                'is_shared' => true,
            ],
        ];

        $totalIncomes = collect($incomesData)->sum('amount');
        $totalExpenses = collect($expensesData)->sum('amount');
        $totalSharedExpenses = collect($expensesData)->where('is_shared', true)->sum('amount');

        $sharesData = [
            [
                'name' => $user1->name,
                'email' => $user1->email,
                'total_income' => collect($incomesData)->where('user_id', $user1->id)->sum('amount'),
                'share_percentage' => (collect($incomesData)->where('user_id', $user1->id)->sum('amount') / $totalIncomes) * 100,
            ],
            [
                'name' => $user2->name,
                'email' => $user2->email,
                'total_income' => collect($incomesData)->where('user_id', $user2->id)->sum('amount'),
                'share_percentage' => (collect($incomesData)->where('user_id', $user2->id)->sum('amount') / $totalIncomes) * 100,
            ],
        ];

        return [
            'month_year' => Carbon::now()->startOfMonth(),
            'incomes_data' => $incomesData,
            'expenses_data' => $expensesData,
            'total_incomes' => $totalIncomes,
            'total_expenses' => $totalExpenses,
            'total_shared_expenses' => $totalSharedExpenses,
            'shares_data' => $sharesData,
        ];
    }

    public function forMonth(Carbon $date): self
    {
        return $this->state(function (array $attributes) use ($date) {
            return [
                'month_year' => $date->copy()->startOfMonth(),
            ];
        });
    }
}
