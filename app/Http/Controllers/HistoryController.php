<?php

namespace App\Http\Controllers;

use App\Models\CustomType;
use App\Models\Expense;
use App\Models\History;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        if (! History::where('month_year', $lastMonth)->exists()) {
            $this->archiveMonth($lastMonth);
        }

        $history = History::orderBy('month_year', 'desc')->get();

        return view('history.index', compact('history'));
    }

    public function create()
    {
        return view('history.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m|unique:history,month_year',
            'incomes' => 'required|array|min:1',
            'incomes.*.description' => 'required|string|max:255',
            'incomes.*.amount' => 'required|numeric|min:0|max:999999999.99',
            'incomes.*.type' => 'required|string|max:50',
            'incomes.*.date' => 'required|date',
            'incomes.*.user_id' => 'required|exists:users,id',
            'expenses' => 'required|array|min:1',
            'expenses.*.description' => 'required|string|max:255',
            'expenses.*.amount' => 'required|numeric|min:0|max:999999999.99',
            'expenses.*.type' => 'required|string|max:50',
            'expenses.*.date' => 'required|date',
            'expenses.*.is_shared' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['incomes'] as $income) {
                CustomType::firstOrCreate([
                    'name' => strtolower($income['type']),
                    'category' => 'income',
                ]);
            }

            foreach ($validated['expenses'] as $expense) {
                CustomType::firstOrCreate([
                    'name' => strtolower($expense['type']),
                    'category' => 'expense',
                ]);
            }

            $startDate = Carbon::parse($validated['month_year'] . '-01')->startOfMonth();

            $expenses = collect($validated['expenses'])->map(function ($expense) {
                $expense['is_shared'] = isset($expense['is_shared']) && $expense['is_shared'] == '1';
                $expense['type'] = strtolower($expense['type']);

                return $expense;
            })->toArray();

            $incomes = collect($validated['incomes'])->map(function ($income) {
                $income['type'] = strtolower($income['type']);

                return $income;
            })->toArray();

            $total_incomes = collect($incomes)->sum('amount');
            $total_expenses = collect($expenses)->sum('amount');
            $total_shared_expenses = collect($expenses)
                ->where('is_shared', true)
                ->sum('amount');

            $shares = collect($incomes)
                ->groupBy('user_id')
                ->map(function ($userIncomes) use ($total_incomes) {
                    $user = DB::table('users')->where('id', $userIncomes->first()['user_id'])->first();

                    return [
                        'name' => $user->name,
                        'email' => $user->email,
                        'total_income' => collect($userIncomes)->sum('amount'),
                        'share_percentage' => ($total_incomes > 0) ? (collect($userIncomes)->sum('amount') / $total_incomes) * 100 : 0,
                    ];
                })->values();

            History::create([
                'month_year' => $startDate,
                'incomes_data' => $incomes,
                'expenses_data' => $expenses,
                'total_incomes' => $total_incomes,
                'total_expenses' => $total_expenses,
                'total_shared_expenses' => $total_shared_expenses,
                'shares_data' => $shares->toArray(),
            ]);

            DB::commit();

            return redirect()->route('history.index')->with('success', 'History added successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while adding history')->withInput();
        }
    }

    public function archiveMonth($date)
    {
        if (History::where('month_year', $date)->exists()) {
            return;
        }

        DB::beginTransaction();

        try {
            $incomes = Income::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->get();

            $expenses = Expense::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->get();

            $total_incomes = $incomes->sum('amount');
            $total_expenses = $expenses->sum('amount');
            $total_shared_expenses = $expenses->where('is_shared', true)->sum('amount');

            $shares = DB::table('incomes')
                ->join('users', 'incomes.user_id', '=', 'users.id')
                ->whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->select('users.name', 'users.email', DB::raw('SUM(amount) as total_income'))
                ->groupBy('users.id', 'users.name', 'users.email')
                ->get()
                ->map(function ($user) use ($total_incomes) {
                    $user->share_percentage = ($total_incomes > 0) ? ($user->total_income / $total_incomes) * 100 : 0;

                    return $user;
                });

            History::create([
                'month_year' => $date,
                'incomes_data' => $incomes->toArray(),
                'expenses_data' => $expenses->toArray(),
                'total_incomes' => $total_incomes,
                'total_expenses' => $total_expenses,
                'total_shared_expenses' => $total_shared_expenses,
                'shares_data' => $shares->toArray(),
            ]);

            Income::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->where('locked', false)
                ->delete();

            Expense::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->where('locked', false)
                ->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function archiveCurrentMonth()
    {
        $currentMonth = Carbon::now()->startOfMonth();

        if (History::where('month_year', $currentMonth)->exists()) {
            return redirect()->back()->withErrors(['error' => 'History for this month already exists'])->withInput();
        }

        try {
            $this->archiveMonth($currentMonth);

            return redirect()->route('dashboard')->with('success', 'Month archived successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while archiving');
        }
    }

    public function show(History $history)
    {
        return view('history.show', compact('history'));
    }

    public function edit(History $history)
    {
        return view('history.edit', compact('history'));
    }

    public function destroy(History $history)
    {
        $history->delete();

        return redirect()->route('history.index')->with('success', 'Month deleted successfully');
    }

    public function update(Request $request, History $history)
    {
        $validated = $request->validate([
            'incomes' => 'required|array|min:1',
            'incomes.*.description' => 'required|string|max:255',
            'incomes.*.amount' => 'required|numeric|min:0|max:999999999.99',
            'incomes.*.type' => 'required|string|max:50',
            'incomes.*.date' => 'required|date',
            'incomes.*.user_id' => 'required|exists:users,id',
            'expenses' => 'required|array|min:1',
            'expenses.*.description' => 'required|string|max:255',
            'expenses.*.amount' => 'required|numeric|min:0|max:999999999.99',
            'expenses.*.type' => 'required|string|max:50',
            'expenses.*.date' => 'required|date',
            'expenses.*.is_shared' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $expenses = collect($validated['expenses'])->map(function ($expense) {
                $expense['is_shared'] = isset($expense['is_shared']) && $expense['is_shared'] == '1';
                $expense['type'] = strtolower($expense['type']);

                return $expense;
            })->toArray();

            $incomes = collect($validated['incomes'])->map(function ($income) {
                $income['type'] = strtolower($income['type']);

                return $income;
            })->toArray();

            $total_incomes = collect($incomes)->sum('amount');
            $total_expenses = collect($expenses)->sum('amount');
            $total_shared_expenses = collect($expenses)
                ->where('is_shared', true)
                ->sum('amount');

            $shares = collect($incomes)
                ->groupBy('user_id')
                ->map(function ($userIncomes) use ($total_incomes) {
                    $user = DB::table('users')->where('id', $userIncomes->first()['user_id'])->first();

                    return [
                        'name' => $user->name,
                        'email' => $user->email,
                        'total_income' => collect($userIncomes)->sum('amount'),
                        'share_percentage' => ($total_incomes > 0) ? (collect($userIncomes)->sum('amount') / $total_incomes) * 100 : 0,
                    ];
                })->values();

            $history->update([
                'incomes_data' => $incomes,
                'expenses_data' => $expenses,
                'total_incomes' => $total_incomes,
                'total_expenses' => $total_expenses,
                'total_shared_expenses' => $total_shared_expenses,
                'shares_data' => $shares->toArray(),
            ]);

            DB::commit();

            return redirect()->route('history.index')->with('success', 'History updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while updating history')->withInput();
        }
    }
}
