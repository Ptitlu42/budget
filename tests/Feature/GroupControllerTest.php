<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class GroupControllerTest extends TestCase
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
        Mail::fake();

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
        Mail::assertSent(\App\Mail\GroupInvitation::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
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

        $response = $this->actingAs($user)
            ->post("/groups/join/{$invitation->token}/confirm");

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
            ->get("/groups/join/{$invitation->token}");

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

    public function test_guest_is_redirected_to_register_when_joining()
    {
        $group = Group::factory()->create();
        $invitation = GroupInvitation::factory()->create([
            'group_id' => $group->id,
            'email' => 'test@example.com',
            'used' => false
        ]);

        $response = $this->get("/groups/join/{$invitation->token}");

        $response->assertRedirect(route('register'));
        $response->assertSessionHas('info', 'Please create an account to join the group.');
        $this->assertEquals($invitation->token, session('group_invitation_token'));
    }
}
