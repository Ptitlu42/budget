<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $fillable = [
        'month_year',
        'incomes_data',
        'expenses_data',
        'total_incomes',
        'total_expenses',
        'total_shared_expenses',
        'shares_data',
    ];

    protected $casts = [
        'month_year' => 'date',
        'incomes_data' => 'array',
        'expenses_data' => 'array',
        'total_incomes' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'total_shared_expenses' => 'decimal:2',
        'shares_data' => 'array',
    ];
}
