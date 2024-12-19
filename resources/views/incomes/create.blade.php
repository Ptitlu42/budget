@extends('layouts.app')

@section('content')
    <div class="gradient-border">
        <div class="bg-dark p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold flex items-center">
                    @if(Auth::user()->email === 'lucas.beyer@gmx.fr')
                        <i class="fas fa-code text-dev mr-2"></i>
                        <span class="text-dev">Ajouter un revenu</span>
                    @else
                        <i class="fas fa-lemon text-lemon mr-2"></i>
                        <span class="text-lemon">Ajouter un revenu</span>
                    @endif
                </h1>
                <a href="{{ route('incomes.index') }}"
                    class="text-white hover:text-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour aux revenus
                </a>
            </div>

            <form action="{{ route('incomes.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="description" class="block text-white mb-2">Description</label>
                        <input type="text" name="description" id="description" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                    </div>

                    <div>
                        <label for="amount" class="block text-white mb-2">Montant</label>
                        <input type="number" step="0.01" name="amount" id="amount" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                    </div>

                    <div>
                        <label for="type" class="block text-white mb-2">Type</label>
                        <select name="type" id="type" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                            <option value="salary">Salaire</option>
                            <option value="aid">Aide</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="date" class="block text-white mb-2">Date</label>
                        <input type="date" name="date" id="date" required
                            class="w-full bg-darker border border-gray-700 rounded px-4 py-2 text-white focus:outline-none focus:border-dev">
                    </div>
                </div>

                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="locked" value="1"
                            class="form-checkbox bg-darker border border-gray-700 text-dev focus:ring-dev">
                        <span class="text-white">Verrouiller ce revenu (ne sera pas supprimé lors de l'archivage mensuel)</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('incomes.index') }}"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                        Annuler
                    </a>
                    <button type="submit"
                        class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'bg-dev hover:bg-purple-600' : 'bg-lemon hover:bg-yellow-400' }} text-white font-bold py-2 px-4 rounded transition hover-scale">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection