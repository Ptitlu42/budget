@extends('layouts.app')

@section('content')
<div class="gradient-border">
    <div class="bg-dark p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold flex items-center">
                @if(Auth::user()->email === 'lucas.beyer@gmx.fr')
                    <i class="fas fa-users text-dev mr-2"></i>
                    <span class="text-dev">Create Group</span>
                @else
                    <i class="fas fa-users text-lemon mr-2"></i>
                    <span class="text-lemon">Create Group</span>
                @endif
            </h1>
        </div>

        <form action="{{ route('groups.store') }}" method="POST" class="glass-effect p-4 rounded">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-white mb-2">Group Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev"
                    placeholder="Enter group name">
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('groups.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                    class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'bg-dev hover:bg-purple-600' : 'bg-lemon hover:bg-yellow-400' }} text-dark font-bold py-2 px-4 rounded transition hover-scale">
                    <i class="fas fa-check mr-2"></i>
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
