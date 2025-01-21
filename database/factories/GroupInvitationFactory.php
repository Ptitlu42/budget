<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\GroupInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GroupInvitationFactory extends Factory
{
    protected $model = GroupInvitation::class;

    public function definition()
    {
        return [
            'group_id' => Group::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'token' => Str::random(32),
            'used' => false,
        ];
    }
}
