<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'type',
        'date',
        'is_shared',
        'locked'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'is_shared' => 'boolean',
        'locked' => 'boolean'
    ];
}
