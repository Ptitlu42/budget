@extends('layouts.app')

@section('content')
<div class="gradient-border">
    <div class="bg-dark p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold flex items-center">
                @if(Auth::user()->email === 'lucas.beyer@gmx.fr')
                    <i class="fas fa-users text-dev mr-2"></i>
                    <span class="text-dev">Group Management</span>
                @else
                    <i class="fas fa-users text-lemon mr-2"></i>
                    <span class="text-lemon">Group Management</span>
                @endif
            </h1>
            @if(!$group)
                <a href="{{ route('groups.create') }}"
                    class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'bg-dev hover:bg-purple-600' : 'bg-lemon hover:bg-yellow-400' }} text-dark font-bold py-2 px-4 rounded transition hover-scale">
                    <i class="fas fa-plus mr-2"></i>
                    Create Group
                </a>
            @endif
        </div>

        @if($group)
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-white">Your Group</h2>
                <div class="glass-effect p-4 rounded">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-white"><strong>Name:</strong> {{ $group->name }}</p>
                            <p class="text-white mt-2"><strong>Members ({{ $members->count() }}):</strong></p>
                            <ul class="list-disc list-inside mt-2 text-white">
                                @foreach($members as $member)
                                    <li>{{ $member->name }} ({{ $member->email }})</li>
                                @endforeach
                            </ul>
                        </div>
                        <form action="{{ route('groups.leave') }}" method="POST" class="ml-4">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition hover-scale"
                                onclick="return confirm('Are you sure you want to leave this group?')">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Leave Group
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-white">Invite Member</h2>
                <form action="{{ route('groups.invite') }}" method="POST" class="glass-effect p-4 rounded">
                    @csrf
                    <div class="flex gap-4">
                        <input type="email" name="email" required placeholder="Email address"
                            class="flex-1 bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                        <button type="submit"
                            class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'bg-dev hover:bg-purple-600' : 'bg-lemon hover:bg-yellow-400' }} text-dark font-bold py-2 px-4 rounded transition hover-scale">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Invitation
                        </button>
                    </div>
                </form>
            </div>

            @if($invitations->isNotEmpty())
                <div>
                    <h2 class="text-xl font-semibold mb-4 text-white">Pending Invitations</h2>
                    <div class="glass-effect p-4 rounded">
                        <ul class="space-y-2">
                            @foreach($invitations as $invitation)
                                <li class="text-white">{{ $invitation->email }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <p class="text-white mb-4">You are not a member of any group.</p>
                <p class="text-gray-400">Create a new group or wait for an invitation to join one.</p>
            </div>
        @endif
    </div>
</div>
@endsection
