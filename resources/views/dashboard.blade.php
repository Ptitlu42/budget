@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/history/styles.css') }}">
@endpush

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h3 class="text-white mb-2 flex items-center">
                    <i class="fas fa-euro-sign mr-2"></i>
                    Revenus Totaux
                </h3>
                <p class="text-2xl font-bold text-white">
                    {{ number_format(App\Models\Income::sum('amount'), 2, ',', ' ') }} €
                </p>
            </div>
        </div>
        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h3 class="text-white mb-2 flex items-center">
                    <i class="fas fa-wallet mr-2"></i>
                    Dépenses Totales
                </h3>
                <p class="text-2xl font-bold text-white">
                    {{ number_format(App\Models\Expense::sum('amount'), 2, ',', ' ') }} €
                </p>
            </div>
        </div>
        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h3 class="text-white mb-2 flex items-center">
                    <i class="fas fa-hand-holding-euro mr-2"></i>
                    Dépenses Communes
                </h3>
                <p class="text-2xl font-bold text-white">
                    {{ number_format(App\Models\Expense::where('is_shared', true)->sum('amount'), 2, ',', ' ') }} €
                </p>
            </div>
        </div>
        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h3 class="text-white mb-2 flex items-center">
                    <i class="fas fa-user-tag mr-2"></i>
                    Dépenses Individuelles
                </h3>
                <p class="text-2xl font-bold text-white">
                    {{ number_format(App\Models\Expense::where('is_shared', false)->sum('amount'), 2, ',', ' ') }} €
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Revenus par personne
                </h2>
                @php
                    $users = DB::table('incomes')
                        ->join('users', 'incomes.user_id', '=', 'users.id')
                        ->select('users.name', 'users.email', DB::raw('SUM(amount) as total_income'))
                        ->groupBy('users.id', 'users.name', 'users.email')
                        ->get();
                    $totalIncomes = App\Models\Income::sum('amount');
                @endphp
                <div class="space-y-4">
                    @foreach($users as $user)
                        <div class="glass-effect p-4 rounded hover-scale">
                            <div class="flex justify-between items-center mb-2">
                                <span class="flex items-center">
                                    @if($user->email === 'lucas.beyer@gmx.fr')
                                        <i class="fas fa-code text-dev mr-2"></i>
                                        <span class="text-dev">{{ $user->name }}</span>
                                    @else
                                        <i class="fas fa-lemon text-lemon mr-2"></i>
                                        <span class="text-lemon">{{ $user->name }}</span>
                                    @endif
                                </span>
                                <div class="flex items-center">
                                    <span class="font-bold {{ $user->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }} mr-4">
                                        {{ number_format($user->total_income, 2, ',', ' ') }} €
                                    </span>
                                    <span class="font-bold {{ $user->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }}">
                                        ({{ number_format(($user->total_income / $totalIncomes) * 100, 1) }}%)
                                    </span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="{{ $user->email === 'lucas.beyer@gmx.fr' ? 'bg-dev' : 'bg-lemon' }} progress-bar" data-width="{{ ($user->total_income / $totalIncomes) * 100 }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Répartition des revenus
                </h2>
                <canvas id="revenusChart" class="w-full" data-users="{{ json_encode($users) }}"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-calculator mr-2"></i>
                    Part des dépenses communes
                </h2>
                @php
                    $totalIncomes = App\Models\Income::sum('amount');
                    $shares = DB::table('incomes')
                        ->join('users', 'incomes.user_id', '=', 'users.id')
                        ->select('users.name', 'users.email', DB::raw('SUM(amount) as total_income'))
                        ->groupBy('users.id', 'users.name', 'users.email')
                        ->get()
                        ->map(function ($user) use ($totalIncomes) {
                            $user->share_percentage = ($totalIncomes > 0) ? ($user->total_income / $totalIncomes) * 100 : 0;
                            return $user;
                        });
                    $totalSharedExpenses = App\Models\Expense::where('is_shared', true)->sum('amount');
                @endphp
                <div class="space-y-4">
                    @foreach($shares as $share)
                        <div class="glass-effect p-4 rounded hover-scale">
                            <div class="flex justify-between items-center mb-2">
                                <span class="flex items-center">
                                    @if($share->email === 'lucas.beyer@gmx.fr')
                                        <i class="fas fa-code text-dev mr-2"></i>
                                        <span class="text-dev">{{ $share->name }}</span>
                                    @else
                                        <i class="fas fa-lemon text-lemon mr-2"></i>
                                        <span class="text-lemon">{{ $share->name }}</span>
                                    @endif
                                </span>
                                <span class="font-bold {{ $share->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }}">
                                    {{ number_format(($totalSharedExpenses * $share->share_percentage / 100), 2, ',', ' ') }} €
                                </span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="{{ $share->email === 'lucas.beyer@gmx.fr' ? 'bg-dev' : 'bg-lemon' }} progress-bar" data-width="{{ $share->share_percentage }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="gradient-border hover-scale">
            <div class="bg-dark p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-tags mr-2"></i>
                    Dépenses par catégorie
                </h2>
                <canvas id="depensesChart" class="w-full" data-expenses="{{ json_encode([
                    'rent' => App\Models\Expense::where('type', 'rent')->sum('amount'),
                    'insurance' => App\Models\Expense::where('type', 'insurance')->sum('amount'),
                    'utilities' => App\Models\Expense::where('type', 'utilities')->sum('amount'),
                    'groceries' => App\Models\Expense::where('type', 'groceries')->sum('amount'),
                    'other' => App\Models\Expense::where('type', 'other')->sum('amount')
                ]) }}"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
