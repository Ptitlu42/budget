@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Join Group</div>

                <div class="card-body">
                    <p>You have been invited to join the group "{{ $group->name }}".</p>

                    <form method="POST" action="{{ route('groups.confirm-join', $token) }}">
                        @csrf
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                Join Group
                            </button>
                            <a href="{{ route('groups.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
