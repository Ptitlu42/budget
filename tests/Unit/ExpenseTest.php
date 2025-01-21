<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use App\Models\Group;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    public function test_expense_has_correct_fillable_attributes(): void
    {
        $expense = new Expense();
        $expectedFillable = [
            'user_id',
            'group_id',
            'description',
            'amount',
            'type',
            'date',
            'is_shared',
            'locked'
        ];

        $this->assertEquals($expectedFillable, $expense->getFillable());
    }

    public function test_expense_has_correct_casts(): void
    {
        $expense = new Expense();
        $expectedCasts = [
            'amount' => 'decimal:2',
            'is_shared' => 'boolean',
            'locked' => 'boolean',
            'date' => 'date'
        ];

        $actualCasts = array_intersect_key($expense->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_expense_factory_creates_valid_expense(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $expense = Expense::factory()->forUser($user)->create();

        $this->assertNotNull($expense->amount);
        $this->assertNotNull($expense->type);
        $this->assertNotNull($expense->description);
        $this->assertNotNull($expense->date);
        $this->assertNotNull($expense->user_id);
        $this->assertNotNull($expense->group_id);
    }

    public function test_expense_amount_is_stored_as_decimal(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $expense = Expense::factory()->forUser($user)->create(['amount' => 123.45]);

        $this->assertEquals(123.45, $expense->amount);
    }

    public function test_expense_type_is_valid(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $expense = Expense::factory()->forUser($user)->create();

        $validTypes = ['rent', 'insurance', 'utilities', 'groceries', 'other'];
        $this->assertTrue(in_array($expense->type, $validTypes));
    }

    public function test_expense_is_shared_defaults_to_true(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $expense = Expense::factory()->forUser($user)->create();

        $this->assertTrue($expense->is_shared);
    }

    public function test_expense_locked_defaults_to_false(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $expense = Expense::factory()->forUser($user)->create();

        $this->assertFalse($expense->locked);
    }
}
