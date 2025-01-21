<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'description',
        'amount',
        'type',
        'date',
        'locked',
        'is_shared',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'locked' => 'boolean',
        'is_shared' => 'boolean',
    ];

    protected $attributes = [
        'locked' => false,
        'is_shared' => true,
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
