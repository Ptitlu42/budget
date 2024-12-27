<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Budget</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        lemon: '#FFD700',
                        dev: '#6366F1',
                        dark: '#1a1a1a',
                        darker: '#0f0f0f'
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @stack('styles')
</head>
<body class="bg-darker text-white min-h-screen">
    <nav class="glass-effect fixed top-0 w-full z-50">
        <div class="container mx-auto flex justify-between items-center p-4">
            <a href="{{ route('dashboard') }}" class="text-white text-2xl font-bold flex items-center hover-scale">
                <span class="mr-2">Budget</span>
                @if(Auth::check())
                    @if(Auth::user()->email === 'lucas.beyer@gmx.fr')
                        <i class="fas fa-code text-dev"></i>
                    @else
                        <i class="fas fa-lemon text-lemon"></i>
                    @endif
                @endif
            </a>
            <div class="space-x-6">
                <a href="{{ route('incomes.index') }}" class="text-white hover:text-dev transition">Incomes</a>
                <a href="{{ route('expenses.index') }}" class="text-white hover:text-lemon transition">Expenses</a>
                <a href="{{ route('history.index') }}" class="text-white hover:text-gray-300 transition">History</a>
                <a href="{{ route('groups.index') }}" class="text-white hover:text-gray-300 transition">Groups</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-300 hover:text-white transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-4 mt-20">
        @if(session('success'))
            <div class="glass-effect text-white p-4 rounded mb-4 hover-scale">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="glass-effect text-red-500 p-4 rounded mb-4 hover-scale">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="glass-effect text-red-500 p-4 rounded mb-4 hover-scale">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
