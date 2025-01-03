@extends('layouts.history')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="gradient-border">
            <div class="bg-dark p-4 md:p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                    <h1 class="text-xl md:text-2xl font-bold flex items-center">
                        <i class="fas fa-history mr-2 text-white"></i>
                        <span class="text-white">Previous Months History</span>
                    </h1>
                    <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                        <a href="{{ route('history.create') }}"
                            class="bg-white hover:bg-gray-100 text-dark font-bold py-2 px-4 rounded transition hover-scale text-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Month
                        </a>
                        <form action="{{ route('history.archive-current') }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit"
                                class="bg-dev hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition hover-scale w-full"
                                onclick="return confirm('Are you sure you want to archive the current month? Unlocked data will be deleted.')">
                                <i class="fas fa-archive mr-2"></i>
                                Archive Current Month
                            </button>
                        </form>
                    </div>
                </div>

                <div class="glass-effect p-4 md:p-6 mb-6">
                    <h2 class="text-lg md:text-xl font-bold mb-4">Income and Expenses Evolution</h2>
                    <div class="h-[400px] md:h-[500px] lg:h-[600px]">
                        <canvas id="evolutionChart"
                            data-months="{{ json_encode($history->map(function($month) { return $month->month_year->format('F Y'); })) }}"
                            data-incomes="{{ json_encode($history->pluck('total_incomes')) }}"
                            data-expenses="{{ json_encode($history->pluck('total_expenses')) }}"
                            data-shared-expenses="{{ json_encode($history->pluck('total_shared_expenses')) }}"
                            data-individual-incomes="{{ json_encode($history->map(function($month) {
                                return collect($month->shares_data)->mapWithKeys(function($share) {
                                    return [$share['name'] => $share['total_income']];
                                });
                            })) }}">
                        </canvas>
                    </div>
                </div>

                <div class="space-y-4 md:space-y-6">
                    @forelse($history as $month)
                        <div class="gradient-border hover-scale">
                            <div class="bg-dark p-4 md:p-6">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                                    <div class="w-full md:w-auto">
                                        <h2 class="text-lg md:text-xl font-bold mb-2">{{ $month->month_year->format('F Y') }}</h2>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-400">Total Income</p>
                                                <p class="text-white font-bold">{{ number_format($month->total_incomes, 2, ',', ' ') }} €</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400">Total Expenses</p>
                                                <p class="text-white font-bold">{{ number_format($month->total_expenses, 2, ',', ' ') }} €</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400">Shared Expenses</p>
                                                <p class="text-white font-bold">{{ number_format($month->total_shared_expenses, 2, ',', ' ') }} €</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end items-center space-x-4 w-full md:w-auto">
                                        <a href="{{ route('history.show', $month) }}"
                                            class="text-white hover:text-gray-300 transition">
                                            <i class="fas fa-eye text-xl md:text-2xl"></i>
                                        </a>
                                        <a href="{{ route('history.edit', $month) }}"
                                            class="text-green-500 hover:text-green-700 transition">
                                            <i class="fas fa-edit text-xl md:text-2xl"></i>
                                        </a>
                                        <form action="{{ route('history.destroy', $month) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 transition"
                                                onclick="return confirm('Are you sure you want to delete this month? This action cannot be undone.')">
                                                <i class="fas fa-trash text-xl md:text-2xl"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="glass-effect p-4 md:p-6 text-center">
                            <p class="text-gray-400">No history available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/history/index.js') }}"></script>
@endpush
