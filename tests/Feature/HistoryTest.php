<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\History;
use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    public function test_history_page_is_displayed(): void
    {
        $response = $this->actingAs($this->user)->get('/history');
        $response->assertStatus(200);
    }

    public function test_history_can_be_created(): void
    {
        $date = Carbon::create(2024, 12, 1);
        $incomes = [
            [
                'description' => 'Test Income',
                'amount' => 1000.00,
                'type' => 'salary',
                'date' => $date->format('Y-m'),
                'user_id' => $this->user->id,
            ],
        ];

        $expenses = [
            [
                'description' => 'Test Expense',
                'amount' => 500.00,
                'type' => 'rent',
                'date' => $date->format('Y-m'),
                'is_shared' => true,
            ],
        ];

        $response = $this->actingAs($this->user)->post('/history', [
            'month_year' => $date->format('Y-m'),
            'incomes' => $incomes,
            'expenses' => $expenses,
        ]);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('history', [
            'month_year' => $date->format('Y-m-d H:i:s'),
            'total_incomes' => 1000.00,
            'total_expenses' => 500.00,
            'total_shared_expenses' => 500.00,
        ]);
    }

    public function test_current_month_can_be_archived(): void
    {
        $date = Carbon::now()->startOfMonth();
        Income::factory()->forUser($this->user)->create([
            'amount' => 1000.00,
            'date' => $date,
            'type' => 'salary',
        ]);

        Expense::factory()->create([
            'amount' => 500.00,
            'date' => $date,
            'is_shared' => true,
            'type' => 'utilities',
        ]);

        $response = $this->actingAs($this->user)->post('/history/archive-current');

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('history', [
            'month_year' => $date->format('Y-m-d H:i:s'),
            'total_incomes' => 1000.00,
            'total_expenses' => 500.00,
            'total_shared_expenses' => 500.00,
        ]);
    }

    public function test_history_can_be_updated(): void
    {
        $history = History::factory()->create([
            'month_year' => Carbon::now()->startOfMonth(),
        ]);

        $updatedData = [
            'incomes' => [
                [
                    'description' => 'Updated Income',
                    'amount' => 2000.00,
                    'type' => 'salary',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'user_id' => $this->user->id,
                ],
            ],
            'expenses' => [
                [
                    'description' => 'Updated Expense',
                    'amount' => 1000.00,
                    'type' => 'rent',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'is_shared' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->put("/history/{$history->id}", $updatedData);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('history', [
            'id' => $history->id,
            'total_incomes' => 2000.00,
            'total_expenses' => 1000.00,
            'total_shared_expenses' => 1000.00,
        ]);
    }

    public function test_history_can_be_updated_with_multiple_entries(): void
    {
        $history = History::factory()->create([
            'month_year' => Carbon::now()->startOfMonth(),
        ]);

        $updatedData = [
            'incomes' => [
                [
                    'description' => 'First Income',
                    'amount' => 2000.00,
                    'type' => 'salary',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'user_id' => $this->user->id,
                ],
                [
                    'description' => 'Second Income',
                    'amount' => 1000.00,
                    'type' => 'aid',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'user_id' => $this->user->id,
                ],
            ],
            'expenses' => [
                [
                    'description' => 'First Expense',
                    'amount' => 1000.00,
                    'type' => 'rent',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'is_shared' => true,
                ],
                [
                    'description' => 'Second Expense',
                    'amount' => 500.00,
                    'type' => 'utilities',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'is_shared' => false,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->put("/history/{$history->id}", $updatedData);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('history', [
            'id' => $history->id,
            'total_incomes' => 3000.00,
            'total_expenses' => 1500.00,
            'total_shared_expenses' => 1000.00,
        ]);

        $history->refresh();
        $this->assertCount(2, $history->incomes_data);
        $this->assertCount(2, $history->expenses_data);
    }

    public function test_history_can_be_updated_with_removed_entries(): void
    {
        $history = History::factory()->create([
            'month_year' => Carbon::now()->startOfMonth(),
            'incomes_data' => [
                [
                    'description' => 'First Income',
                    'amount' => 2000.00,
                    'type' => 'salary',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'user_id' => $this->user->id,
                ],
                [
                    'description' => 'Second Income',
                    'amount' => 1000.00,
                    'type' => 'aid',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'user_id' => $this->user->id,
                ],
            ],
            'expenses_data' => [
                [
                    'description' => 'First Expense',
                    'amount' => 1000.00,
                    'type' => 'rent',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'is_shared' => true,
                ],
                [
                    'description' => 'Second Expense',
                    'amount' => 500.00,
                    'type' => 'utilities',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'is_shared' => false,
                ],
            ],
            'total_incomes' => 3000.00,
            'total_expenses' => 1500.00,
            'total_shared_expenses' => 1000.00,
        ]);

        $updatedData = [
            'incomes' => [
                [
                    'description' => 'First Income',
                    'amount' => 2000.00,
                    'type' => 'salary',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'user_id' => $this->user->id,
                ],
            ],
            'expenses' => [
                [
                    'description' => 'First Expense',
                    'amount' => 1000.00,
                    'type' => 'rent',
                    'date' => Carbon::now()->format('Y-m-d'),
                    'is_shared' => true,
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->put("/history/{$history->id}", $updatedData);

        $response->assertRedirect('/history');
        $this->assertDatabaseHas('history', [
            'id' => $history->id,
            'total_incomes' => 2000.00,
            'total_expenses' => 1000.00,
            'total_shared_expenses' => 1000.00,
        ]);

        $history->refresh();
        $this->assertCount(1, $history->incomes_data);
        $this->assertCount(1, $history->expenses_data);
    }

    public function test_history_can_be_deleted(): void
    {
        $history = History::factory()->create([
            'month_year' => Carbon::now()->startOfMonth(),
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/history/{$history->id}");

        $response->assertRedirect('/history');
        $this->assertDatabaseMissing('history', ['id' => $history->id]);
    }

    public function test_history_validation_rules(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/history', [
                'month_year' => 'not-a-date',
                'incomes' => 'not-an-array',
                'expenses' => 'not-an-array',
            ]);

        $response->assertSessionHasErrors(['month_year', 'incomes', 'expenses']);
    }

    public function test_cannot_archive_already_archived_month(): void
    {
        $date = Carbon::now()->startOfMonth();
        History::factory()->create([
            'month_year' => $date,
        ]);

        Income::factory()->forUser($this->user)->create([
            'amount' => 1000.00,
            'date' => $date,
            'type' => 'salary',
        ]);

        $response = $this->actingAs($this->user)
            ->post('/history/archive-current');

        $response->assertRedirect()->withErrors(['error' => 'History for this month already exists']);
        $this->assertDatabaseCount('history', 1);
    }

    public function test_history_update_validation_rules(): void
    {
        $history = History::factory()->create();

        $response = $this->actingAs($this->user)
            ->put("/history/{$history->id}", [
                'incomes' => 'not-an-array',
                'expenses' => 'not-an-array',
            ]);

        $response->assertSessionHasErrors(['incomes', 'expenses']);
    }
}
