<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomType extends Model
{
    protected $fillable = ['name', 'category'];

    public static function getIncomeTypes()
    {
        return self::where('category', 'income')->pluck('name');
    }

    public static function getExpenseTypes()
    {
        return self::where('category', 'expense')->pluck('name');
    }
}
