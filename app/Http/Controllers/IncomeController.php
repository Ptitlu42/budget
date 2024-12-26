<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::where('user_id', Auth::id())->orderBy('date', 'desc')->get();
        return view('incomes.index', compact('incomes'));
    }

    public function create()
    {
        return view('incomes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:salary,aid,other',
            'date' => 'required|date'
        ]);

        $validated['user_id'] = Auth::id();
        Income::create($validated);

        return redirect()->route('incomes.index')->with('success', 'Income added successfully');
    }

    public function edit(Income $income)
    {
        if ($income->locked) {
            return redirect()->route('incomes.index')->with('error', 'Cannot edit a locked income');
        }

        return view('incomes.edit', compact('income'));
    }

    public function update(Request $request, Income $income)
    {
        if ($income->locked) {
            return response()->json(['message' => 'Cannot modify a locked income'], 403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:salary,aid,other',
            'date' => 'required|date'
        ]);

        $income->update($validated);

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully');
    }

    public function destroy(Income $income)
    {
        if ($income->locked) {
            return response()->json(['message' => 'Cannot delete a locked income'], 403);
        }

        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Income deleted successfully');
    }
}
