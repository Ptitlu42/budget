<?php

namespace Database\Factories;

use App\Models\History;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition(): array
    {
        return [
            'month_year' => $this->faker->dateTimeBetween('now', '+1 year'),
            'data' => [
                'incomes' => [],
                'expenses' => []
            ]
        ];
    }

    public function forUser(User $user): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
                'group_id' => $user->group_id
            ];
        });
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
