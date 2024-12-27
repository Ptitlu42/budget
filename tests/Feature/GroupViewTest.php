<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_groups_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertViewIs('groups.index');
        $response->assertSeeText('Group Management');
    }

    public function test_user_can_view_create_group_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('groups.create'));

        $response->assertStatus(200);
        $response->assertViewIs('groups.create');
        $response->assertSeeText('Create Group');
    }

    public function test_user_cannot_view_create_group_form_if_in_group()
    {
        $group = Group::create(['name' => 'Test Group']);
        $user = User::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user)
            ->get(route('groups.create'));

        $response->assertRedirect(route('groups.index'));
        $response->assertSessionHas('error', 'You are already a member of a group');
    }

    public function test_user_can_see_group_members()
    {
        $group = Group::create(['name' => 'Test Group']);
        $user1 = User::factory()->create(['group_id' => $group->id]);
        $user2 = User::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user1)
            ->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertSeeText($user1->name);
        $response->assertSeeText($user2->name);
        $response->assertSeeText($user1->email);
        $response->assertSeeText($user2->email);
    }

    public function test_user_can_see_pending_invitations()
    {
        $group = Group::create(['name' => 'Test Group']);
        $user = User::factory()->create(['group_id' => $group->id]);
        $invitation = GroupInvitation::create([
            'group_id' => $group->id,
            'email' => 'test@example.com',
            'token' => 'test-token',
            'used' => false,
        ]);

        $response = $this->actingAs($user)
            ->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Pending Invitations');
        $response->assertSeeText($invitation->email);
    }

    public function test_user_cannot_see_used_invitations()
    {
        $group = Group::create(['name' => 'Test Group']);
        $user = User::factory()->create(['group_id' => $group->id]);
        $invitation = GroupInvitation::create([
            'group_id' => $group->id,
            'email' => 'test@example.com',
            'token' => 'test-token',
            'used' => true,
        ]);

        $response = $this->actingAs($user)
            ->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertDontSeeText($invitation->email);
    }
}
