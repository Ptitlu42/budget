<?php

namespace Database\Seeders;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $sharedExpenses = [
            [
                'description' => 'Loyer',
                'amount' => 580,
                'type' => 'rent',
            ],
            [
                'description' => 'Electricité',
                'amount' => 120,
                'type' => 'utilities',
            ],
            [
                'description' => 'Eau',
                'amount' => 20,
                'type' => 'utilities',
            ],
            [
                'description' => 'Mutuelle',
                'amount' => 50,
                'type' => 'insurance',
            ],
            [
                'description' => 'Assurance maison',
                'amount' => 15,
                'type' => 'insurance',
            ],
        ];

        foreach ($sharedExpenses as $expense) {
            Expense::create([
                'description' => $expense['description'],
                'amount' => $expense['amount'],
                'type' => $expense['type'],
                'date' => Carbon::now(),
                'is_shared' => true,
            ]);
        }
    }
}
