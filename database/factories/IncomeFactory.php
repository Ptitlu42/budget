<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 1000, 5000),
            'type' => 'salary',
            'description' => $this->faker->sentence(),
            'date' => $this->faker->date(),
            'is_shared' => true,
            'locked' => false,
        ];
    }

    public function locked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'locked' => true,
            ];
        });
    }

    public function forUser(User $user): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
                'group_id' => $user->group_id,
            ];
        });
    }
}
