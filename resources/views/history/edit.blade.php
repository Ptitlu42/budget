@extends('layouts.history')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="gradient-border">
            <div class="bg-dark p-4 md:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl md:text-2xl font-bold flex items-center">
                        <i class="fas fa-edit mr-2 text-white"></i>
                        <span class="text-white">Edit History - {{ $history->month_year->format('F Y') }}</span>
                    </h1>
                </div>

                @if($errors->any())
                    <div class="glass-effect p-4 mb-6">
                        <div class="text-red-500">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('history.update', $history) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="glass-effect p-4 md:p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-white">Income</h3>
                            <button type="button" onclick="addIncome()"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition hover-scale">
                                <i class="fas fa-plus mr-2"></i>Add Income
                            </button>
                        </div>
                        <div class="space-y-4" id="incomes-container">
                            @foreach($history->data['incomes'] as $index => $income)
                                <div class="gradient-border income-entry">
                                    <div class="bg-dark p-4">
                                        <div class="flex justify-between mb-4">
                                            <h4 class="text-white font-bold">Income #{{ $index + 1 }}</h4>
                                            <button type="button" onclick="removeEntry(this, 'income')"
                                                class="text-red-500 hover:text-red-700 transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div class="col-span-2">
                                                <label class="block text-sm font-medium text-gray-400">Description</label>
                                                <input type="text"
                                                    name="incomes[{{ $index }}][description]"
                                                    value="{{ $income['description'] }}"
                                                    class="form-input bg-darker border-gray-700 text-white w-full"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-400">Amount</label>
                                                <input type="number"
                                                    step="0.01"
                                                    name="incomes[{{ $index }}][amount]"
                                                    value="{{ $income['amount'] }}"
                                                    class="form-input bg-darker border-gray-700 text-white w-full"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-400">Type</label>
                                                <select name="incomes[{{ $index }}][type]"
                                                    class="form-select bg-darker border-gray-700 text-white w-full"
                                                    required>
                                                    <option value="salary" {{ $income['type'] === 'salary' ? 'selected' : '' }}>Salary</option>
                                                    <option value="aid" {{ $income['type'] === 'aid' ? 'selected' : '' }}>Aid</option>
                                                    <option value="other" {{ $income['type'] === 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="incomes[{{ $index }}][date]" value="{{ $income['date'] }}">
                                        <input type="hidden" name="incomes[{{ $index }}][user_id]" value="{{ $income['user_id'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass-effect p-4 md:p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-white">Expenses</h3>
                            <button type="button" onclick="addExpense()"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition hover-scale">
                                <i class="fas fa-plus mr-2"></i>Add Expense
                            </button>
                        </div>
                        <div class="space-y-4" id="expenses-container">
                            @foreach($history->data['expenses'] as $index => $expense)
                                <div class="gradient-border expense-entry">
                                    <div class="bg-dark p-4">
                                        <div class="flex justify-between mb-4">
                                            <h4 class="text-white font-bold">Expense #{{ $index + 1 }}</h4>
                                            <button type="button" onclick="removeEntry(this, 'expense')"
                                                class="text-red-500 hover:text-red-700 transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                            <div class="col-span-2">
                                                <label class="block text-sm font-medium text-gray-400">Description</label>
                                                <input type="text"
                                                    name="expenses[{{ $index }}][description]"
                                                    value="{{ $expense['description'] }}"
                                                    class="form-input bg-darker border-gray-700 text-white w-full"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-400">Amount</label>
                                                <input type="number"
                                                    step="0.01"
                                                    name="expenses[{{ $index }}][amount]"
                                                    value="{{ $expense['amount'] }}"
                                                    class="form-input bg-darker border-gray-700 text-white w-full"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-400">Type</label>
                                                <select name="expenses[{{ $index }}][type]"
                                                    class="form-select bg-darker border-gray-700 text-white w-full"
                                                    required>
                                                    <option value="rent" {{ $expense['type'] === 'rent' ? 'selected' : '' }}>Rent</option>
                                                    <option value="utilities" {{ $expense['type'] === 'utilities' ? 'selected' : '' }}>Utilities</option>
                                                    <option value="insurance" {{ $expense['type'] === 'insurance' ? 'selected' : '' }}>Insurance</option>
                                                    <option value="groceries" {{ $expense['type'] === 'groceries' ? 'selected' : '' }}>Groceries</option>
                                                    <option value="other" {{ $expense['type'] === 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="flex items-center">
                                                <label class="inline-flex items-center mt-2">
                                                    <input type="checkbox"
                                                        name="expenses[{{ $index }}][is_shared]"
                                                        value="1"
                                                        {{ $expense['is_shared'] ? 'checked' : '' }}
                                                        class="form-checkbox bg-darker border-gray-700 text-dev">
                                                    <span class="ml-2 text-gray-400">Shared</span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="expenses[{{ $index }}][date]" value="{{ $expense['date'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('history.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition hover-scale">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-dev hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition hover-scale">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <template id="income-template">
        <div class="gradient-border income-entry">
            <div class="bg-dark p-4">
                <div class="flex justify-between mb-4">
                    <h4 class="text-white font-bold">New Income</h4>
                    <button type="button" onclick="removeEntry(this, 'income')"
                        class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-400">Description</label>
                        <input type="text" name="incomes[INDEX][description]" class="form-input bg-darker border-gray-700 text-white w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Amount</label>
                        <input type="number" step="0.01" name="incomes[INDEX][amount]" class="form-input bg-darker border-gray-700 text-white w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Type</label>
                        <select name="incomes[INDEX][type]" class="form-select bg-darker border-gray-700 text-white w-full" required>
                            <option value="salary">Salary</option>
                            <option value="aid">Aid</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="incomes[INDEX][date]" value="{{ $history->month_year->format('Y-m-d') }}">
                <input type="hidden" name="incomes[INDEX][user_id]" value="{{ auth()->id() }}">
            </div>
        </div>
    </template>

    <template id="expense-template">
        <div class="gradient-border expense-entry">
            <div class="bg-dark p-4">
                <div class="flex justify-between mb-4">
                    <h4 class="text-white font-bold">New Expense</h4>
                    <button type="button" onclick="removeEntry(this, 'expense')"
                        class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-400">Description</label>
                        <input type="text" name="expenses[INDEX][description]" class="form-input bg-darker border-gray-700 text-white w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Amount</label>
                        <input type="number" step="0.01" name="expenses[INDEX][amount]" class="form-input bg-darker border-gray-700 text-white w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Type</label>
                        <select name="expenses[INDEX][type]" class="form-select bg-darker border-gray-700 text-white w-full" required>
                            <option value="rent">Rent</option>
                            <option value="utilities">Utilities</option>
                            <option value="insurance">Insurance</option>
                            <option value="groceries">Groceries</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="inline-flex items-center mt-2">
                            <input type="checkbox" name="expenses[INDEX][is_shared]" value="1" class="form-checkbox bg-darker border-gray-700 text-dev">
                            <span class="ml-2 text-gray-400">Shared</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="expenses[INDEX][date]" value="{{ $history->month_year->format('Y-m-d') }}">
            </div>
        </div>
    </template>

    <script>
        function updateIndexes(container, type) {
            const entries = container.querySelectorAll(`.${type}-entry`);
            entries.forEach((entry, index) => {
                entry.querySelectorAll('input, select').forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                    }
                });
                const title = entry.querySelector('h4');
                if (title) {
                    title.textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} #${index + 1}`;
                }
            });
        }

        function addIncome() {
            const container = document.getElementById('incomes-container');
            const template = document.getElementById('income-template');
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
            updateIndexes(container, 'income');
        }

        function addExpense() {
            const container = document.getElementById('expenses-container');
            const template = document.getElementById('expense-template');
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
            updateIndexes(container, 'expense');
        }

        function removeEntry(button, type) {
            const entry = button.closest(`.${type}-entry`);
            const container = document.getElementById(`${type}s-container`);
            entry.remove();
            updateIndexes(container, type);
        }
    </script>
@endsection
