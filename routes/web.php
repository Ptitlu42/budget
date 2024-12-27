<?php

use App\Http\Controllers\CustomTypeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('groups/join/{token}', [GroupController::class, 'join'])->name('groups.join');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('incomes', IncomeController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('history', HistoryController::class);
    Route::post('history/archive-current', [HistoryController::class, 'archiveCurrentMonth'])->name('history.archive-current');
    Route::post('custom-types', [CustomTypeController::class, 'store'])->name('custom-types.store');

    Route::get('groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('groups', [GroupController::class, 'store'])->name('groups.store');
    Route::post('groups/invite', [GroupController::class, 'invite'])->name('groups.invite');
    Route::post('groups/join/{token}/confirm', [GroupController::class, 'confirmJoin'])->name('groups.confirm-join');
    Route::post('groups/leave', [GroupController::class, 'leave'])->name('groups.leave');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

require __DIR__.'/auth.php';
