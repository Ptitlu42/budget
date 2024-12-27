<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'type' => $this->faker->randomElement(['rent', 'insurance', 'utilities', 'groceries', 'other']),
            'description' => $this->faker->sentence(),
            'date' => $this->faker->date(),
            'is_shared' => true,
            'locked' => false,
        ];
    }

    public function locked()
    {
        return $this->state(function (array $attributes) {
            return [
                'locked' => true,
            ];
        });
    }

    public function shared()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_shared' => true,
            ];
        });
    }

    public function notShared()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_shared' => false,
            ];
        });
    }
}
