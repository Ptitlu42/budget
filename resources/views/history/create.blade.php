@extends('layouts.history')

@section('content')
    <div class="gradient-border">
        <div class="bg-dark p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-white"></i>
                    <span class="text-white">Ajouter un mois à l'historique</span>
                </h1>
                <a href="{{ route('history.index') }}"
                    class="text-white hover:text-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à l'historique
                </a>
            </div>

            <form action="{{ route('history.store') }}" method="POST" class="space-y-6" id="historyForm">
                @csrf

                <div>
                    <label for="month_year" class="block text-white mb-2">Mois</label>
                    <input type="month" name="month_year" id="month_year" required
                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                </div>

                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-white">Revenus</h2>
                    <div id="incomes-container">
                        <div class="income-entry glass-effect p-4 rounded space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-white mb-2">Description</label>
                                    <input type="text" name="incomes[0][description]" required
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                </div>
                                <div>
                                    <label class="block text-white mb-2">Montant</label>
                                    <input type="number" step="0.01" name="incomes[0][amount]" required
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-white mb-2">Type</label>
                                    <input type="text" name="incomes[0][type]" required list="income-types"
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                    <datalist id="income-types">
                                        @foreach(\App\Models\CustomType::getIncomeTypes() as $type)
                                            <option value="{{ ucfirst($type) }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block text-white mb-2">Date</label>
                                    <input type="date" name="incomes[0][date]" required
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                </div>
                                <div>
                                    <label class="block text-white mb-2">Utilisateur</label>
                                    <select name="incomes[0][user_id]" required
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-income"
                        class="bg-dev hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un revenu
                    </button>
                </div>

                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-white">Dépenses</h2>
                    <div id="expenses-container">
                        <div class="expense-entry glass-effect p-4 rounded space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-white mb-2">Description</label>
                                    <input type="text" name="expenses[0][description]" required
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                </div>
                                <div>
                                    <label class="block text-white mb-2">Montant</label>
                                    <input type="number" step="0.01" name="expenses[0][amount]" required
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-white mb-2">Type</label>
                                    <input type="text" name="expenses[0][type]" required list="expense-types"
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                    <datalist id="expense-types">
                                        @foreach(\App\Models\CustomType::getExpenseTypes() as $type)
                                            <option value="{{ ucfirst($type) }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <div>
                                    <label class="block text-white mb-2">Date</label>
                                    <input type="date" name="expenses[0][date]" required
                                        class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                                </div>
                            </div>
                            <div>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="expenses[0][is_shared]" value="1"
                                        class="form-checkbox bg-darker border border-gray-700 text-dev focus:ring-dev">
                                    <span class="text-white">Dépense partagée</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-expense"
                        class="bg-dev hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter une dépense
                    </button>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('history.index') }}"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                        Annuler
                    </a>
                    <button type="submit"
                        class="bg-dev hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script src="{{ asset('js/history/create.js') }}"></script>
@endpush
