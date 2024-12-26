<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('date', 'desc')->get();

        $totalIncomes = Income::sum('amount');
        $shares = DB::table('incomes')
            ->join('users', 'incomes.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', DB::raw('SUM(amount) as total_income'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->get()
            ->map(function ($user) use ($totalIncomes) {
                $user->share_percentage = ($totalIncomes > 0) ? ($user->total_income / $totalIncomes) * 100 : 0;

                return $user;
            });

        return view('expenses.index', compact('expenses', 'shares'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:rent,utilities,insurance,groceries,other',
            'date' => 'required|date',
            'is_shared' => 'boolean',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully');
    }

    public function edit(Expense $expense)
    {
        if ($expense->locked) {
            return redirect()->route('expenses.index')->with('error', 'Cannot edit a locked expense');
        }

        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->locked) {
            return response()->json(['message' => 'Cannot modify a locked expense'], 403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:rent,utilities,insurance,groceries,other',
            'date' => 'required|date',
            'is_shared' => 'boolean',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->locked) {
            return response()->json(['message' => 'Cannot delete a locked expense'], 403);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully');
    }
}
