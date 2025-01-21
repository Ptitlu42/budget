<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'description',
        'amount',
        'type',
        'date',
        'is_shared',
        'locked',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_shared' => 'boolean',
        'locked' => 'boolean',
        'date' => 'date',
    ];

    protected $attributes = [
        'is_shared' => true,
        'locked' => false,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
