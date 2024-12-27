<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_group()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/groups', [
                'name' => 'Test Group'
            ]);

        $response->assertRedirect(route('groups.index'));
        $this->assertDatabaseHas('groups', [
            'name' => 'Test Group'
        ]);
        $this->assertEquals('Test Group', $user->fresh()->group->name);
    }

    public function test_user_cannot_create_group_if_already_in_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user)
            ->post('/groups', [
                'name' => 'Another Group'
            ]);

        $response->assertRedirect(route('groups.index'));
        $response->assertSessionHas('error', 'You are already a member of a group');
    }

    public function test_user_can_invite_to_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user)
            ->post('/groups/invite', [
                'email' => 'test@example.com'
            ]);

        $response->assertRedirect(route('groups.index'));
        $this->assertDatabaseHas('group_invitations', [
            'email' => 'test@example.com',
            'group_id' => $group->id,
            'used' => false
        ]);
    }

    public function test_user_cannot_invite_if_not_in_group()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/groups/invite', [
                'email' => 'test@example.com'
            ]);

        $response->assertRedirect(route('groups.index'));
        $response->assertSessionHas('error', 'You must first create or join a group');
    }

    public function test_user_can_join_group_with_valid_invitation()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $invitation = GroupInvitation::factory()->create([
            'group_id' => $group->id,
            'email' => $user->email,
            'used' => false
        ]);

        // First, get the confirmation page
        $response = $this->actingAs($user)
            ->get(route('groups.join', $invitation->token));
        $response->assertOk();
        $response->assertViewIs('groups.confirm-join');

        // Then, confirm joining
        $response = $this->actingAs($user)
            ->post(route('groups.confirm-join', $invitation->token));
        $response->assertRedirect(route('groups.index'));
        $this->assertEquals($group->id, $user->fresh()->group_id);
        $this->assertTrue($invitation->fresh()->used);
    }

    public function test_user_cannot_join_group_with_invalid_invitation()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $invitation = GroupInvitation::factory()->create([
            'group_id' => $group->id,
            'email' => 'other@example.com',
            'used' => false
        ]);

        $response = $this->actingAs($user)
            ->get(route('groups.join', $invitation->token));

        $response->assertRedirect(route('groups.index'));
        $response->assertSessionHas('error', 'This invitation is not for you');
    }

    public function test_user_can_leave_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user)
            ->post('/groups/leave');

        $response->assertRedirect(route('groups.index'));
        $this->assertNull($user->fresh()->group_id);
    }

    public function test_group_is_deleted_when_last_member_leaves()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create(['group_id' => $group->id]);

        $response = $this->actingAs($user)
            ->post('/groups/leave');

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }

    public function test_guest_can_register_and_join_group()
    {
        $group = Group::factory()->create();
        $invitation = GroupInvitation::factory()->create([
            'group_id' => $group->id,
            'email' => 'test@example.com',
            'used' => false
        ]);

        // First, visit the invitation link
        $response = $this->get(route('groups.join', $invitation->token));
        $response->assertRedirect(route('register'));
        $this->assertEquals($invitation->token, session('group_invitation_token'));
        $this->assertEquals('test@example.com', session('invitation_email'));

        // Then register with the same email
        $response = $this->followingRedirects()->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Should end up on the join confirmation page
        $response->assertViewIs('groups.confirm-join');
        $response->assertSee($group->name);

        // User should be authenticated
        $user = User::where('email', 'test@example.com')->first();
        $this->assertAuthenticatedAs($user);

        // Confirm joining the group
        $response = $this->actingAs($user)
            ->post(route('groups.confirm-join', $invitation->token));

        $response->assertRedirect(route('groups.index'));
        $this->assertEquals($group->id, $user->fresh()->group_id);
        $this->assertTrue($invitation->fresh()->used);
    }
}
