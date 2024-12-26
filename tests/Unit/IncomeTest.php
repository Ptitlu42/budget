<?php

namespace Tests\Unit;

use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_income_has_correct_fillable_attributes(): void
    {
        $income = new Income();
        $expectedFillable = [
            'user_id',
            'description',
            'amount',
            'type',
            'date',
            'locked',
        ];

        $this->assertEquals($expectedFillable, $income->getFillable());
    }

    public function test_income_has_correct_casts(): void
    {
        $income = new Income();
        $expectedCasts = [
            'date' => 'date',
            'amount' => 'decimal:2',
            'locked' => 'boolean',
        ];

        $actualCasts = array_intersect_key($income->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_income_factory_creates_valid_income(): void
    {
        $user = User::factory()->create();
        $income = Income::factory()->forUser($user)->create();

        $this->assertInstanceOf(Income::class, $income);
        $this->assertNotNull($income->description);
        $this->assertIsNumeric($income->amount);
        $this->assertContains($income->type, ['salary', 'aid', 'other']);
        $this->assertEquals($user->id, $income->user_id);
    }

    public function test_income_amount_is_stored_as_decimal(): void
    {
        $income = Income::factory()->create([
            'amount' => 1000.50,
        ]);

        $this->assertEquals(1000.50, $income->amount);
        $this->assertIsNumeric($income->amount);
    }

    public function test_income_type_is_valid(): void
    {
        $income = Income::factory()->create([
            'type' => 'salary',
        ]);

        $this->assertEquals('salary', $income->type);
        $this->assertContains($income->type, ['salary', 'aid', 'other']);
    }

    public function test_income_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $income = Income::factory()->forUser($user)->create();

        $this->assertInstanceOf(User::class, $income->user);
        $this->assertEquals($user->id, $income->user->id);
    }

    public function test_income_locked_defaults_to_false(): void
    {
        $income = new Income();
        $this->assertFalse($income->locked);
    }
}
