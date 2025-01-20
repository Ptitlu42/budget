@extends('layouts.history')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="gradient-border">
            <div class="bg-dark p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-history mr-2 text-white"></i>
                        <span class="text-white">Historique de {{ $history->month_year->format('F Y') }}</span>
                    </h1>
                    <a href="{{ route('history.index') }}"
                        class="text-white hover:text-gray-300 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à l'historique
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="glass-effect p-4 rounded">
                        <h3 class="text-lg font-semibold mb-2">Revenus totaux</h3>
                        <p class="text-2xl font-bold">{{ number_format(collect($history->data['incomes'])->sum('amount'), 2, ',', ' ') }} €</p>
                    </div>
                    <div class="glass-effect p-4 rounded">
                        <h3 class="text-lg font-semibold mb-2">Dépenses totales</h3>
                        <p class="text-2xl font-bold">{{ number_format(collect($history->data['expenses'])->sum('amount'), 2, ',', ' ') }} €</p>
                    </div>
                    <div class="glass-effect p-4 rounded">
                        <h3 class="text-lg font-semibold mb-2">Dépenses communes</h3>
                        <p class="text-2xl font-bold">{{ number_format(collect($history->data['expenses'])->where('is_shared', true)->sum('amount'), 2, ',', ' ') }} €</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="glass-effect p-4 rounded">
                        <h3 class="text-lg font-semibold mb-4">Répartition des revenus</h3>
                        <canvas id="incomesChart"
                            data-shares="{{ json_encode(collect($history->data['incomes'])->groupBy('user_id')->map(function($incomes) {
                                $user = \App\Models\User::find($incomes->first()['user_id']);
                                return [
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'total_income' => $incomes->sum('amount'),
                                    'share_percentage' => (collect($history->data['incomes'])->sum('amount') > 0) ? ($incomes->sum('amount') / collect($history->data['incomes'])->sum('amount') * 100) : 0,
                                ];
                            })->values()) }}"
                            data-total-expenses="{{ collect($history->data['expenses'])->sum('amount') }}"
                            data-total-shared-expenses="{{ collect($history->data['expenses'])->where('is_shared', true)->sum('amount') }}">
                        </canvas>
                    </div>
                    <div class="glass-effect p-4 rounded">
                        <h3 class="text-lg font-semibold mb-4">Répartition des dépenses</h3>
                        <canvas id="expensesChart"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="glass-effect p-4 rounded">
                        <h3 class="text-lg font-semibold mb-4">Détail des revenus</h3>
                        <div class="space-y-4">
                            @foreach($history->data['incomes'] as $income)
                                <div class="border-b border-gray-700 pb-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold">{{ $income['description'] }}</p>
                                            <p class="text-sm text-gray-400">
                                                {{ \Carbon\Carbon::parse($income['date'])->format('d/m/Y') }} - {{ ucfirst($income['type']) }}
                                            </p>
                                        </div>
                                        <p class="font-bold">{{ number_format($income['amount'], 2, ',', ' ') }} €</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass-effect p-4 rounded">
                        <h3 class="text-lg font-semibold mb-4">Détail des dépenses</h3>
                        <div class="space-y-4">
                            @foreach($history->data['expenses'] as $expense)
                                <div class="border-b border-gray-700 pb-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold">{{ $expense['description'] }}</p>
                                            <p class="text-sm text-gray-400">
                                                {{ \Carbon\Carbon::parse($expense['date'])->format('d/m/Y') }} - {{ ucfirst($expense['type']) }}
                                                @if($expense['is_shared'])
                                                    <span class="ml-2 px-2 py-1 bg-dev text-xs rounded">Partagée</span>
                                                @endif
                                            </p>
                                        </div>
                                        <p class="font-bold">{{ number_format($expense['amount'], 2, ',', ' ') }} €</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/history/show.js') }}"></script>
@endpush
