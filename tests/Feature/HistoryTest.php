<?php

namespace Tests\Feature;

use App\Models\History;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $group;

    protected function setUp(): void
    {
        parent::setUp();

        $this->group = Group::factory()->create();
        $this->user = User::factory()->create(['group_id' => $this->group->id]);
    }

    public function test_history_page_is_displayed(): void
    {
        $response = $this->actingAs($this->user)->get('/history');
        $response->assertStatus(200);
    }

    public function test_history_can_be_created(): void
    {
        $date = Carbon::create(2025, 1, 1);
        $incomes = [
            [
                'description' => 'Test Income',
                'amount' => 1000.00,
                'type' => 'salary',
                'user_id' => $this->user->id
            ]
        ];
        $expenses = [
            [
                'description' => 'Test Expense',
                'amount' => 500.00,
                'type' => 'rent',
                'is_shared' => true
            ]
        ];

        $response = $this->actingAs($this->user)->post('/history', [
            'month_year' => $date->format('Y-m'),
            'data' => [
                'incomes' => $incomes,
                'expenses' => $expenses
            ]
        ]);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('history', [
            'month_year' => $date->format('Y-m-d H:i:s'),
            'user_id' => $this->user->id,
            'group_id' => $this->group->id
        ]);
    }

    public function test_current_month_can_be_archived(): void
    {
        $date = Carbon::create(2025, 1, 1);
        Carbon::setTestNow($date);

        $response = $this->actingAs($this->user)->post('/history/archive-current');

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('history', [
            'month_year' => $date->format('Y-m-d H:i:s'),
            'user_id' => $this->user->id,
            'group_id' => $this->group->id
        ]);
    }

    public function test_history_can_be_updated(): void
    {
        $history = History::factory()->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id
        ]);

        $newIncomes = [
            [
                'description' => 'Updated Income',
                'amount' => 1500.00,
                'type' => 'salary',
                'user_id' => $this->user->id
            ]
        ];
        $newExpenses = [
            [
                'description' => 'Updated Expense',
                'amount' => 750.00,
                'type' => 'rent',
                'is_shared' => true
            ]
        ];

        $response = $this->actingAs($this->user)->put("/history/{$history->id}", [
            'data' => [
                'incomes' => $newIncomes,
                'expenses' => $newExpenses
            ]
        ]);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('history', [
            'id' => $history->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id
        ]);
    }

    public function test_history_can_be_deleted(): void
    {
        $history = History::factory()->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id
        ]);

        $response = $this->actingAs($this->user)->delete("/history/{$history->id}");

        $response->assertRedirect('/history');
        $this->assertDatabaseMissing('history', ['id' => $history->id]);
    }

    public function test_history_validation_rules(): void
    {
        $response = $this->actingAs($this->user)->post('/history', [
            'month_year' => '',
            'data' => ''
        ]);

        $response->assertSessionHasErrors(['month_year', 'data']);
    }

    public function test_cannot_archive_already_archived_month(): void
    {
        $date = Carbon::create(2025, 1, 1);
        Carbon::setTestNow($date);

        History::factory()->create([
            'month_year' => $date,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id
        ]);

        $response = $this->actingAs($this->user)->post('/history/archive-current');

        $response->assertRedirect()->withErrors(['month' => 'History for this month already exists']);
    }
}
