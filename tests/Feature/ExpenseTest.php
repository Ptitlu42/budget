<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_expense_page_is_displayed(): void
    {
        $response = $this->actingAs($this->user)->get('/expenses');
        $response->assertStatus(200);
    }

    public function test_expense_can_be_created(): void
    {
        $expenseData = [
            'description' => 'Test Expense',
            'amount' => 100.50,
            'type' => 'utilities',
            'date' => '2024-01-01',
            'is_shared' => true,
        ];

        $response = $this->actingAs($this->user)
            ->post('/expenses', $expenseData);

        $response->assertRedirect('/expenses');
        $this->assertDatabaseHas('expenses', array_merge($expenseData, [
            'date' => Carbon::parse($expenseData['date'])->format('Y-m-d H:i:s'),
            'is_shared' => true,
        ]));
    }

    public function test_expense_can_be_updated(): void
    {
        $expense = Expense::factory()->create([
            'type' => 'utilities',
        ]);

        $updatedData = [
            'description' => 'Updated Expense',
            'amount' => 200.75,
            'type' => 'rent',
            'date' => '2024-01-02',
            'is_shared' => false,
        ];

        $response = $this->actingAs($this->user)
            ->put("/expenses/{$expense->id}", $updatedData);

        $response->assertRedirect('/expenses');
        $this->assertDatabaseHas('expenses', array_merge($updatedData, [
            'date' => Carbon::parse($updatedData['date'])->format('Y-m-d H:i:s'),
            'is_shared' => false,
        ]));
    }

    public function test_expense_can_be_deleted(): void
    {
        $expense = Expense::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete("/expenses/{$expense->id}");

        $response->assertRedirect('/expenses');
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_expense_validation_rules(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/expenses', [
                'description' => '',
                'amount' => 'not-a-number',
                'type' => 'invalid-type',
                'date' => 'not-a-date',
                'is_shared' => 'not-a-boolean',
            ]);

        $response->assertSessionHasErrors(['description', 'amount', 'type', 'date', 'is_shared']);
    }

    public function test_locked_expense_cannot_be_modified(): void
    {
        $expense = Expense::factory()->locked()->create();
        $originalData = $expense->toArray();

        $response = $this->actingAs($this->user)
            ->put("/expenses/{$expense->id}", [
                'description' => 'Try to update locked expense',
                'amount' => 200,
                'type' => 'utilities',
                'date' => '2024-01-02',
                'is_shared' => false,
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'description' => $originalData['description'],
            'amount' => $originalData['amount'],
            'type' => $originalData['type'],
        ]);
    }
}
