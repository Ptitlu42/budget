@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen items-center justify-center">
        <div class="gradient-border w-full max-w-md">
            <div class="bg-dark p-8">
                <h1 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-sign-in-alt mr-2 text-white"></i>
                    <span class="text-white">Login</span>
                </h1>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-white mb-2">Email</label>
                        <input type="email" name="email" id="email" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev"
                            value="{{ old('email') }}" autofocus>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-white mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="rounded bg-darker border-gray-700 text-dev focus:ring-dev">
                        <label for="remember" class="text-white ml-2">Remember me</label>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('register') }}"
                            class="text-dev hover:text-purple-400 transition">
                            Create an account
                        </a>

                        <button type="submit"
                            class="bg-dev hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
