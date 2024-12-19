<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'amount',
        'type',
        'date',
        'locked'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'locked' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
