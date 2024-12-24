@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen items-center justify-center">
        <div class="gradient-border w-full max-w-md">
            <div class="bg-dark p-8">
                <h1 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-user-plus mr-2 text-white"></i>
                    <span class="text-white">Créer un compte</span>
                </h1>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-white mb-2">Nom</label>
                        <input type="text" name="name" id="name" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev"
                            value="{{ old('name') }}" autofocus>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-white mb-2">Email</label>
                        <input type="email" name="email" id="email" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-white mb-2">Mot de passe</label>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-white mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('login') }}"
                            class="text-dev hover:text-purple-400 transition">
                            Déjà inscrit ?
                        </a>

                        <button type="submit"
                            class="bg-dev hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                            S'inscrire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
