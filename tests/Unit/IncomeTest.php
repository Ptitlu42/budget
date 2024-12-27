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
            'group_id',
            'description',
            'amount',
            'type',
            'date',
            'locked',
            'is_shared',
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
            'is_shared' => 'boolean',
        ];

        $actualCasts = array_intersect_key($income->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_income_factory_creates_valid_income(): void
    {
        $income = Income::factory()->create();

        $this->assertInstanceOf(Income::class, $income);
        $this->assertNotNull($income->description);
        $this->assertNotNull($income->amount);
        $this->assertNotNull($income->type);
        $this->assertNotNull($income->date);
    }

    public function test_income_amount_is_stored_as_decimal(): void
    {
        $income = Income::factory()->create([
            'amount' => 100.50,
        ]);

        $this->assertEquals(100.50, $income->amount);
    }

    public function test_income_type_is_valid(): void
    {
        $income = Income::factory()->create();

        $validTypes = ['salary', 'aid', 'other'];
        $this->assertTrue(in_array($income->type, $validTypes));
    }

    public function test_income_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $income = Income::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $income->user);
        $this->assertEquals($user->id, $income->user->id);
    }

    public function test_income_locked_defaults_to_false(): void
    {
        $income = new Income();
        $this->assertFalse($income->locked);
    }

    public function test_income_is_shared_defaults_to_true(): void
    {
        $income = new Income();
        $this->assertTrue($income->is_shared);
    }
}
