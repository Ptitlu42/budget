<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use App\Models\Income;
use App\Models\Expense;
use App\Models\History;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_expected_data(): void
    {
        $this->seed();

        // Check users
        $users = User::all();
        $this->assertCount(2, $users);
        $this->assertEquals('Lemon', $users->first()->name);
        $this->assertEquals("P'tit Lu", $users->last()->name);

        // Check groups
        $groups = Group::all();
        $this->assertCount(1, $groups);
        $this->assertEquals('Test Group', $groups->first()->name);

        // Check expenses
        $expenses = Expense::all();
        $this->assertCount(6, $expenses);
        $this->assertEquals('rent', $expenses->first()->type);
        $this->assertTrue($expenses->first()->is_shared);

        // Check incomes
        $incomes = Income::all();
        $this->assertCount(6, $incomes);
        $this->assertEquals('salary', $incomes->first()->type);
        $this->assertTrue($incomes->first()->is_shared);

        // Check history
        $history = History::all();
        $this->assertCount(6, $history);
        $this->assertArrayHasKey('incomes', $history->first()->data);
        $this->assertArrayHasKey('expenses', $history->first()->data);
    }

    public function test_model_relationships(): void
    {
        $this->seed();

        $user = User::first();
        $group = Group::first();

        // Test User relationships
        $this->assertInstanceOf(Group::class, $user->group);
        $this->assertCount(3, $user->expenses);
        $this->assertCount(3, $user->incomes);
        $this->assertCount(3, $user->histories);

        // Test Group relationships
        $this->assertCount(2, $group->users);
        $this->assertCount(6, $group->expenses);
        $this->assertCount(6, $group->incomes);
        $this->assertCount(6, $group->histories);
    }
}
