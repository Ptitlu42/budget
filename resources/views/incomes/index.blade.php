@extends('layouts.app')

@section('content')
    <div class="gradient-border">
        <div class="bg-dark p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold flex items-center">
                    @if(Auth::user()->email === 'lucas.beyer@gmx.fr')
                        <i class="fas fa-code text-dev mr-2"></i>
                        <span class="text-dev">Mes Revenus</span>
                    @else
                        <i class="fas fa-lemon text-lemon mr-2"></i>
                        <span class="text-lemon">Mes Revenus</span>
                    @endif
                </h1>
                <a href="{{ route('incomes.create') }}"
                    class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'bg-dev hover:bg-purple-600' : 'bg-lemon hover:bg-yellow-400' }} text-dark font-bold py-2 px-4 rounded transition hover-scale">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un revenu
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }} border-b border-gray-700">
                            <th class="py-2 px-4 text-left">Date</th>
                            <th class="py-2 px-4 text-left">Description</th>
                            <th class="py-2 px-4 text-left">Type</th>
                            <th class="py-2 px-4 text-right">Montant</th>
                            <th class="py-2 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incomes as $income)
                            <tr class="border-b border-gray-700 hover:bg-gray-800">
                                <td class="py-2 px-4">{{ $income->date->format('d/m/Y') }}</td>
                                <td class="py-2 px-4">{{ $income->description }}</td>
                                <td class="py-2 px-4">
                                    @switch($income->type)
                                        @case('salary')
                                            <span class="flex items-center">
                                                <i class="fas fa-money-bill-wave mr-2 {{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }}"></i>
                                                Salaire
                                            </span>
                                            @break
                                        @case('aid')
                                            <span class="flex items-center">
                                                <i class="fas fa-hand-holding-heart mr-2 {{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }}"></i>
                                                Aide
                                            </span>
                                            @break
                                        @default
                                            <span class="flex items-center">
                                                <i class="fas fa-plus-circle mr-2 {{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }}"></i>
                                                Autre
                                            </span>
                                    @endswitch
                                </td>
                                <td class="py-2 px-4 text-right font-bold {{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }}">
                                    {{ number_format($income->amount, 2, ',', ' ') }} €
                                </td>
                                <td class="py-2 px-4 text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        <a href="{{ route('incomes.edit', $income) }}"
                                            class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'text-dev hover:text-purple-400' : 'text-lemon hover:text-yellow-400' }} transition">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce revenu ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @if($income->locked)
                                            <span class="{{ Auth::user()->email === 'lucas.beyer@gmx.fr' ? 'text-dev' : 'text-lemon' }}">
                                                <i class="fas fa-lock" title="Ce revenu est verrouillé"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
