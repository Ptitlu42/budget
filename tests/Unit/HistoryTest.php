<?php

namespace Tests\Unit;

use App\Models\History;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_has_correct_fillable_attributes(): void
    {
        $history = new History();
        $expectedFillable = [
            'month_year',
            'incomes_data',
            'expenses_data',
            'total_incomes',
            'total_expenses',
            'total_shared_expenses',
            'shares_data'
        ];

        $this->assertEquals($expectedFillable, $history->getFillable());
    }

    public function test_history_has_correct_casts(): void
    {
        $history = new History();
        $expectedCasts = [
            'month_year' => 'date',
            'incomes_data' => 'array',
            'expenses_data' => 'array',
            'total_incomes' => 'decimal:2',
            'total_expenses' => 'decimal:2',
            'total_shared_expenses' => 'decimal:2',
            'shares_data' => 'array'
        ];

        $actualCasts = array_intersect_key($history->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_history_factory_creates_valid_history(): void
    {
        $history = History::factory()->create();

        $this->assertInstanceOf(History::class, $history);
        $this->assertInstanceOf(Carbon::class, $history->month_year);
        $this->assertIsArray($history->incomes_data);
        $this->assertIsArray($history->expenses_data);
        $this->assertIsNumeric($history->total_incomes);
        $this->assertIsNumeric($history->total_expenses);
        $this->assertIsNumeric($history->total_shared_expenses);
        $this->assertIsArray($history->shares_data);
    }

    public function test_history_amounts_are_stored_as_decimal(): void
    {
        $history = History::factory()->create([
            'total_incomes' => 1000.50,
            'total_expenses' => 500.25,
            'total_shared_expenses' => 250.75
        ]);

        $this->assertEquals(1000.50, $history->total_incomes);
        $this->assertEquals(500.25, $history->total_expenses);
        $this->assertEquals(250.75, $history->total_shared_expenses);
        $this->assertIsNumeric($history->total_incomes);
        $this->assertIsNumeric($history->total_expenses);
        $this->assertIsNumeric($history->total_shared_expenses);
    }

    public function test_history_data_is_stored_as_array(): void
    {
        $incomesData = [
            [
                'description' => 'Test Income',
                'amount' => 1000.00,
                'type' => 'salary'
            ]
        ];

        $expensesData = [
            [
                'description' => 'Test Expense',
                'amount' => 500.00,
                'type' => 'rent'
            ]
        ];

        $history = History::factory()->create([
            'incomes_data' => $incomesData,
            'expenses_data' => $expensesData
        ]);

        $this->assertEquals($incomesData, $history->incomes_data);
        $this->assertEquals($expensesData, $history->expenses_data);
        $this->assertIsArray($history->incomes_data);
        $this->assertIsArray($history->expenses_data);
    }

    public function test_history_month_year_is_stored_as_date(): void
    {
        $date = Carbon::create(2024, 1, 1);
        $history = History::factory()->create([
            'month_year' => $date
        ]);

        $this->assertEquals($date->format('Y-m-d'), $history->month_year->format('Y-m-d'));
        $this->assertInstanceOf(Carbon::class, $history->month_year);
    }
}
