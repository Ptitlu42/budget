<?php

namespace Tests\Unit;

use App\Models\Income;
use App\Models\User;
use App\Models\Group;
use Tests\TestCase;

class IncomeTest extends TestCase
{
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
            'is_shared'
        ];

        $this->assertEquals($expectedFillable, $income->getFillable());
    }

    public function test_income_has_correct_casts(): void
    {
        $income = new Income();
        $expectedCasts = [
            'amount' => 'decimal:2',
            'is_shared' => 'boolean',
            'locked' => 'boolean',
            'date' => 'date'
        ];

        $actualCasts = array_intersect_key($income->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    public function test_income_factory_creates_valid_income(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $income = Income::factory()->forUser($user)->create();

        $this->assertNotNull($income->amount);
        $this->assertNotNull($income->type);
        $this->assertNotNull($income->description);
        $this->assertNotNull($income->date);
        $this->assertNotNull($income->user_id);
        $this->assertNotNull($income->group_id);
    }

    public function test_income_amount_is_stored_as_decimal(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $income = Income::factory()->forUser($user)->create(['amount' => 123.45]);

        $this->assertEquals(123.45, $income->amount);
    }

    public function test_income_type_is_valid(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $income = Income::factory()->forUser($user)->create();

        $validTypes = ['salary', 'bonus', 'investment', 'other'];
        $this->assertTrue(in_array($income->type, $validTypes));
    }

    public function test_income_is_shared_defaults_to_true(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $income = Income::factory()->forUser($user)->create();

        $this->assertTrue($income->is_shared);
    }

    public function test_income_locked_defaults_to_false(): void
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);
        $income = Income::factory()->forUser($user)->create();

        $this->assertFalse($income->locked);
    }
}
