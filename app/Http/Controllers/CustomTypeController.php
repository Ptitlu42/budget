<?php

namespace App\Http\Controllers;

use App\Models\CustomType;
use Illuminate\Http\Request;

class CustomTypeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:income,expense',
        ]);

        try {
            CustomType::create($validated);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ce type existe déjà',
            ], 422);
        }
    }
}
