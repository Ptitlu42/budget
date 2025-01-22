<?php

namespace App\Http\Controllers;

use App\Models\CustomType;
use App\Models\Expense;
use App\Models\History;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $history = History::where('group_id', $user->group_id)
            ->orderBy('month_year', 'desc')
            ->get();

        return view('history.index', compact('history'));
    }

    public function create()
    {
        return view('history.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m',
            'data' => 'required|array',
            'data.incomes' => 'required|array',
            'data.expenses' => 'required|array'
        ]);

        $date = Carbon::createFromFormat('Y-m', $validated['month_year'])->startOfMonth();
        $user = Auth::user();

        if (History::where('group_id', $user->group_id)
            ->where('month_year', $date)
            ->exists()) {
            return redirect()->back()->withErrors(['month_year' => 'A history already exists for this month in your group.']);
        }

        $history = History::create([
            'user_id' => $user->id,
            'group_id' => $user->group_id,
            'month_year' => $date,
            'data' => $validated['data']
        ]);

        return redirect('/history');
    }

    public function archiveMonth()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->startOfMonth();

        if (History::where('group_id', $user->group_id)
            ->where('month_year', $currentMonth)
            ->exists()) {
            return redirect()->back()->withErrors(['month' => 'History for this month already exists']);
        }

        // Get all group incomes
        $incomes = Income::with(['user'])
            ->where('group_id', $user->group_id)
            ->get()
            ->map(function ($income) {
                return [
                    'id' => $income->id,
                    'user_id' => $income->user_id,
                    'group_id' => $income->group_id,
                    'amount' => $income->amount,
                    'type' => $income->type,
                    'description' => $income->description,
                    'date' => $income->date->format('Y-m-d'),
                    'is_shared' => $income->is_shared,
                    'locked' => $income->locked,
                    'user' => [
                        'id' => $income->user->id,
                        'name' => $income->user->name
                    ]
                ];
            });

        $expenses = Expense::with(['user'])
            ->where('group_id', $user->group_id)
            ->get()
            ->map(function ($expense) {
                return [
                    'id' => $expense->id,
                    'user_id' => $expense->user_id,
                    'group_id' => $expense->group_id,
                    'amount' => $expense->amount,
                    'type' => $expense->type,
                    'description' => $expense->description,
                    'date' => $expense->date->format('Y-m-d'),
                    'is_shared' => $expense->is_shared,
                    'locked' => $expense->locked,
                    'user' => [
                        'id' => $expense->user->id,
                        'name' => $expense->user->name
                    ]
                ];
            });

        History::create([
            'user_id' => $user->id,
            'group_id' => $user->group_id,
            'month_year' => $currentMonth,
            'data' => [
                'incomes' => $incomes->toArray(),
                'expenses' => $expenses->toArray()
            ]
        ]);

        return redirect()->route('dashboard');
    }

    public function show(History $history)
    {
        if ($history->group_id !== Auth::user()->group_id) {
            abort(403, 'You do not have access to this history.');
        }
        return view('history.show', compact('history'));
    }

    public function edit(History $history)
    {
        if ($history->group_id !== Auth::user()->group_id) {
            abort(403, 'You do not have access to this history.');
        }
        return view('history.edit', compact('history'));
    }

    public function destroy(History $history)
    {
        if ($history->group_id !== Auth::user()->group_id) {
            abort(403, 'You do not have access to this history.');
        }
        $history->delete();
        return redirect('/history');
    }

    public function update(Request $request, History $history)
    {
        if ($history->group_id !== Auth::user()->group_id) {
            abort(403, 'You do not have access to this history.');
        }

        $validated = $request->validate([
            'data' => 'required|array',
            'data.incomes' => 'required|array',
            'data.expenses' => 'required|array'
        ]);

        $history->update([
            'data' => $validated['data']
        ]);

        return redirect('/history');
    }
}
