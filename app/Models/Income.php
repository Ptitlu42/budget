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
        'description',
        'amount',
        'type',
        'date',
        'locked',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'locked' => 'boolean',
    ];

    protected $attributes = [
        'locked' => false,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
