<?php

namespace Database\Factories;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->words(3, true),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'type' => $this->faker->randomElement(['rent', 'utilities', 'insurance', 'food', 'other']),
            'date' => Carbon::now(),
            'is_shared' => $this->faker->boolean(80),
            'locked' => false,
        ];
    }

    public function locked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'locked' => true,
                'type' => 'utilities',
            ];
        });
    }

    public function shared(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_shared' => true,
            ];
        });
    }

    public function notShared(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_shared' => false,
            ];
        });
    }
}
