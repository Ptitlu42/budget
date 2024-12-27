<?php

namespace Tests\Unit;

use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_has_correct_fillable_attributes(): void
    {
        $expense = new Expense();
        $fillable = [
            'user_id',
            'group_id',
            'description',
            'amount',
            'type',
            'date',
            'is_shared',
            'locked',
        ];

        $this->assertEquals($fillable, $expense->getFillable());
    }

    public function test_expense_has_correct_casts(): void
    {
        $expense = new Expense();
        $expectedCasts = [
            'amount' => 'decimal:2',
            'is_shared' => 'boolean',
            'locked' => 'boolean',
            'date' => 'date',
        ];

        $actualCasts = array_intersect_key($expense->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_expense_factory_creates_valid_expense(): void
    {
        $expense = Expense::factory()->create();

        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertNotNull($expense->description);
        $this->assertNotNull($expense->amount);
        $this->assertNotNull($expense->type);
        $this->assertNotNull($expense->date);
    }

    public function test_expense_amount_is_stored_as_decimal(): void
    {
        $expense = Expense::factory()->create([
            'amount' => 100.50,
        ]);

        $this->assertEquals(100.50, $expense->amount);
    }

    public function test_expense_type_is_valid(): void
    {
        $expense = Expense::factory()->create();

        $validTypes = ['rent', 'insurance', 'utilities', 'groceries', 'other'];
        $this->assertTrue(in_array($expense->type, $validTypes));
    }

    public function test_expense_is_shared_defaults_to_true(): void
    {
        $expense = new Expense();
        $this->assertTrue($expense->is_shared);
    }

    public function test_expense_locked_defaults_to_false(): void
    {
        $expense = new Expense();
        $this->assertFalse($expense->locked);
    }
}
