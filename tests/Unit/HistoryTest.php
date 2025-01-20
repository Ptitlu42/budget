<?php

namespace Tests\Unit;

use App\Models\History;
use App\Models\User;
use App\Models\Group;
use Tests\TestCase;
use Carbon\Carbon;

class HistoryTest extends TestCase
{
    public function test_history_has_correct_fillable_attributes(): void
    {
        $history = new History();
        $expectedFillable = [
            'user_id',
            'group_id',
            'month_year',
            'data'
        ];

        $this->assertEquals($expectedFillable, $history->getFillable());
    }

    public function test_history_has_correct_casts(): void
    {
        $history = new History();
        $expectedCasts = [
            'month_year' => 'date',
            'data' => 'array'
        ];

        $actualCasts = array_intersect_key($history->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_history_factory_creates_valid_history(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $history = History::factory()->forUser($user)->create();

        $this->assertNotNull($history->month_year);
        $this->assertNotNull($history->data);
        $this->assertNotNull($history->user_id);
        $this->assertNotNull($history->group_id);
        $this->assertArrayHasKey('incomes', $history->data);
        $this->assertArrayHasKey('expenses', $history->data);
    }

    public function test_history_month_year_is_stored_as_date(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $history = History::factory()->forUser($user)->create([
            'month_year' => '2024-01'
        ]);

        $this->assertInstanceOf(Carbon::class, $history->month_year);
        $this->assertEquals('2024-01', $history->month_year->format('Y-m'));
    }

    public function test_history_data_is_stored_as_array(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $history = History::factory()->forUser($user)->create([
            'data' => [
                'incomes' => [],
                'expenses' => []
            ]
        ]);

        $this->assertIsArray($history->data);
        $this->assertArrayHasKey('incomes', $history->data);
        $this->assertArrayHasKey('expenses', $history->data);
    }
}
