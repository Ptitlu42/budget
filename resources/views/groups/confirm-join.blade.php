@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen items-center justify-center">
        <div class="gradient-border w-full max-w-md">
            <div class="bg-dark p-8">
                <h1 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-users mr-2 text-white"></i>
                    <span class="text-white">Join Group</span>
                </h1>

                <div class="bg-darker rounded-lg p-6 mb-6 border border-gray-700">
                    <h2 class="text-lg font-semibold text-white mb-2">Group Information</h2>
                    <p class="text-gray-300 mb-4">
                        You have been invited to join the group <span class="text-dev font-semibold">{{ $group->name }}</span>.
                    </p>

                    <div class="border-t border-gray-700 my-4"></div>

                    <form method="POST" action="{{ route('groups.confirm-join', $token) }}" class="space-y-6">
                        @csrf
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('groups.index') }}"
                                class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600 transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-dev hover:bg-purple-600 text-white font-bold rounded transition-colors hover-scale">
                                Join Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
