<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;
        $members = $group ? $group->users : collect();
        $invitations = $group ? $group->invitations()->where('used', false)->get() : collect();

        return view('groups.index', compact('group', 'members', 'invitations'));
    }

    public function create()
    {
        if (Auth::user()->group_id) {
            return redirect()->route('groups.index')
                ->with('error', 'You are already a member of a group');
        }

        return view('groups.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->group_id) {
            return redirect()->route('groups.index')
                ->with('error', 'You are already a member of a group');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create($validated);
        $user = User::find(Auth::id());
        $user->group_id = $group->id;
        $user->save();

        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully');
    }

    public function invite(Request $request)
    {
        $user = Auth::user();
        if (!$user->group_id) {
            return redirect()->route('groups.index')
                ->with('error', 'You must first create or join a group');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        if (User::where('email', $validated['email'])->whereNotNull('group_id')->exists()) {
            return redirect()->route('groups.index')
                ->with('error', 'This user is already a member of a group');
        }

        $token = Str::random(32);
        GroupInvitation::create([
            'group_id' => $user->group_id,
            'email' => $validated['email'],
            'token' => $token,
        ]);

        Mail::to($validated['email'])->send(new \App\Mail\GroupInvitation(
            $token,
            $user->group->name,
            $user->name
        ));

        return redirect()->route('groups.index')
            ->with('success', 'Invitation sent successfully');
    }

    public function join($token)
    {
        $invitation = GroupInvitation::where('token', $token)
            ->where('used', false)
            ->firstOrFail();

        if (!Auth::check()) {
            session(['group_invitation_token' => $token]);
            session(['invitation_email' => $invitation->email]);
            return redirect()->route('register')
                ->with('info', 'Please create an account to join the group.');
        }

        if (Auth::user()->group_id) {
            return redirect()->route('groups.index')
                ->with('error', 'You are already a member of a group');
        }

        if (Auth::user()->email !== $invitation->email) {
            return redirect()->route('groups.index')
                ->with('error', 'This invitation is not for you');
        }

        return view('groups.confirm-join', [
            'group' => $invitation->group,
            'token' => $token
        ]);
    }

    public function confirmJoin($token)
    {
        $invitation = GroupInvitation::where('token', $token)
            ->where('used', false)
            ->firstOrFail();

        $user = User::find(Auth::id());
        $user->group_id = $invitation->group_id;
        $user->save();

        $invitation->used = true;
        $invitation->save();

        return redirect()->route('groups.index')
            ->with('success', 'You have successfully joined the group');
    }

    public function leave()
    {
        $user = User::find(Auth::id());
        if (!$user->group_id) {
            return redirect()->route('groups.index')
                ->with('error', 'You are not a member of any group');
        }

        $group = $user->group;
        $user->group_id = null;
        $user->save();

        if ($group->users()->count() === 0) {
            $group->delete();
        }

        return redirect()->route('groups.index')
            ->with('success', 'You have successfully left the group');
    }
}
