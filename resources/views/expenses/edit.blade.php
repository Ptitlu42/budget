@extends('layouts.app')

@section('content')
    <div class="gradient-border">
        <div class="bg-dark p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-edit mr-2 text-white"></i>
                    <span class="text-white">Modifier une dépense</span>
                </h1>
                <a href="{{ route('expenses.index') }}"
                    class="text-white hover:text-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour aux dépenses
                </a>
            </div>

            <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="description" class="block text-white mb-2">Description</label>
                        <input type="text" name="description" id="description" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev"
                            value="{{ $expense->description }}">
                    </div>

                    <div>
                        <label for="amount" class="block text-white mb-2">Montant</label>
                        <input type="number" step="0.01" name="amount" id="amount" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev"
                            value="{{ $expense->amount }}">
                    </div>

                    <div>
                        <label for="type" class="block text-white mb-2">Type</label>
                        <select name="type" id="type" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                            <option value="rent" {{ $expense->type === 'rent' ? 'selected' : '' }}>Loyer</option>
                            <option value="insurance" {{ $expense->type === 'insurance' ? 'selected' : '' }}>Assurance</option>
                            <option value="utilities" {{ $expense->type === 'utilities' ? 'selected' : '' }}>Charges</option>
                            <option value="groceries" {{ $expense->type === 'groceries' ? 'selected' : '' }}>Courses</option>
                            <option value="other" {{ $expense->type === 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="date" class="block text-white mb-2">Date</label>
                        <input type="date" name="date" id="date" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev"
                            value="{{ $expense->date->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_shared" value="1" {{ $expense->is_shared ? 'checked' : '' }}
                                class="form-checkbox bg-darker border border-gray-700 text-dev focus:ring-dev">
                            <span class="text-white">Dépense partagée</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="locked" value="1" {{ $expense->locked ? 'checked' : '' }}
                                class="form-checkbox bg-darker border border-gray-700 text-dev focus:ring-dev">
                            <span class="text-white">Verrouiller cette dépense (ne sera pas supprimée lors de l'archivage mensuel)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('expenses.index') }}"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                        Annuler
                    </a>
                    <button type="submit"
                        class="bg-white hover:bg-gray-100 text-dark font-bold py-2 px-4 rounded transition hover-scale">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
