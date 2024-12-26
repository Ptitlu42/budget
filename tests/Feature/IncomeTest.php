<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Income;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class IncomeTest extends TestCase
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

    public function test_income_page_is_displayed(): void
    {
        $response = $this->actingAs($this->user)->get('/incomes');
        $response->assertStatus(200);
    }

    public function test_income_can_be_created(): void
    {
        $incomeData = [
            'description' => 'Test Income',
            'amount' => 1000.50,
            'type' => 'salary',
            'date' => '2024-01-01'
        ];

        $response = $this->actingAs($this->user)
            ->post('/incomes', $incomeData);

        $response->assertRedirect('/incomes');
        $this->assertDatabaseHas('incomes', array_merge($incomeData, [
            'user_id' => $this->user->id,
            'date' => Carbon::parse($incomeData['date'])->format('Y-m-d H:i:s')
        ]));
    }

    public function test_income_can_be_updated(): void
    {
        $income = Income::factory()->forUser($this->user)->create();
        $updatedData = [
            'description' => 'Updated Income',
            'amount' => 2000.75,
            'type' => 'aid',
            'date' => '2024-01-02'
        ];

        $response = $this->actingAs($this->user)
            ->put("/incomes/{$income->id}", $updatedData);

        $response->assertRedirect('/incomes');
        $this->assertDatabaseHas('incomes', array_merge($updatedData, [
            'user_id' => $this->user->id,
            'date' => Carbon::parse($updatedData['date'])->format('Y-m-d H:i:s')
        ]));
    }

    public function test_income_can_be_deleted(): void
    {
        $income = Income::factory()->forUser($this->user)->create();

        $response = $this->actingAs($this->user)
            ->delete("/incomes/{$income->id}");

        $response->assertRedirect('/incomes');
        $this->assertDatabaseMissing('incomes', ['id' => $income->id]);
    }

    public function test_user_can_only_see_their_own_incomes(): void
    {
        $userIncome = Income::factory()->forUser($this->user)->create();
        $otherIncome = Income::factory()->forUser($this->otherUser)->create();

        $response = $this->actingAs($this->user)->get('/incomes');

        $response->assertSee($userIncome->description);
        $response->assertDontSee($otherIncome->description);
    }

    public function test_income_validation_rules(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/incomes', [
                'description' => '',
                'amount' => 'not-a-number',
                'type' => 'invalid-type',
                'date' => 'not-a-date'
            ]);

        $response->assertSessionHasErrors(['description', 'amount', 'type', 'date']);
    }

    public function test_locked_income_cannot_be_modified(): void
    {
        $income = Income::factory()->forUser($this->user)->locked()->create();
        $originalData = $income->toArray();

        $response = $this->actingAs($this->user)
            ->put("/incomes/{$income->id}", [
                'description' => 'Try to update locked income',
                'amount' => 2000,
                'type' => 'aid',
                'date' => '2024-01-02'
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('incomes', [
            'id' => $income->id,
            'description' => $originalData['description'],
            'amount' => $originalData['amount'],
            'type' => $originalData['type']
        ]);
    }
}
