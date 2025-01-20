<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'type' => 'rent',
            'description' => $this->faker->sentence(),
            'date' => $this->faker->date(),
            'is_shared' => true,
            'locked' => false,
        ];
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
