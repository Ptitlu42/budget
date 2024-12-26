<?php

namespace Tests\Unit;

use App\Models\Expense;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_has_correct_fillable_attributes(): void
    {
        $expense = new Expense();
        $fillable = [
            'description',
            'amount',
            'type',
            'date',
            'is_shared',
            'locked'
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
            'date' => 'date'
        ];

        $actualCasts = array_intersect_key($expense->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_expense_factory_creates_valid_expense(): void
    {
        $expense = Expense::factory()->create();

        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertNotNull($expense->description);
        $this->assertIsNumeric($expense->amount);
        $this->assertContains($expense->type, ['rent', 'utilities', 'insurance', 'food', 'other']);
        $this->assertIsBool($expense->is_shared);
        $this->assertIsBool($expense->locked);
    }

    public function test_expense_amount_is_stored_as_decimal(): void
    {
        $expense = Expense::factory()->create([
            'amount' => 1000.50,
            'type' => 'utilities'
        ]);

        $this->assertEquals(1000.50, $expense->amount);
        $this->assertIsNumeric($expense->amount);
    }

    public function test_expense_type_is_valid(): void
    {
        $expense = Expense::factory()->create([
            'type' => 'utilities'
        ]);

        $this->assertEquals('utilities', $expense->type);
        $this->assertContains($expense->type, ['rent', 'utilities', 'insurance', 'food', 'other']);
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
