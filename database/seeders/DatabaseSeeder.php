<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Income;
use App\Models\Expense;
use App\Models\History;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create a group
        $group = Group::create([
            'name' => 'Test Group'
        ]);

        // Create two users
        $user1 = User::create([
            'name' => 'Lemon',
            'email' => 'lemon@example.com',
            'password' => bcrypt('password'),
            'group_id' => $group->id
        ]);

        $user2 = User::create([
            'name' => "P'tit Lu",
            'email' => 'ptitlu@example.com',
            'password' => bcrypt('password'),
            'group_id' => $group->id
        ]);

        // Create incomes for each user
        foreach ([$user1, $user2] as $user) {
            Income::factory()
                ->count(3)
                ->forUser($user)
                ->create();
        }

        // Create expenses for each user
        foreach ([$user1, $user2] as $user) {
            Expense::factory()
                ->count(3)
                ->forUser($user)
                ->create();
        }

        // Create history records for each user
        foreach ([$user1, $user2] as $user) {
            History::factory()
                ->count(3)
                ->forUser($user)
                ->create();
        }
    }
}
