<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->words(3, true),
            'amount' => $this->faker->randomFloat(2, 100, 2000),
            'type' => $this->faker->randomElement(['salary', 'aid', 'other']),
            'date' => Carbon::now(),
            'user_id' => User::factory(),
            'locked' => false
        ];
    }

    public function locked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'locked' => true
            ];
        });
    }

    public function forUser(User $user): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id
            ];
        });
    }
}
