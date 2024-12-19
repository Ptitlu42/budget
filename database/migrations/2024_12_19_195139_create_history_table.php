<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->date('month_year');
            $table->json('incomes_data');
            $table->json('expenses_data');
            $table->decimal('total_incomes', 10, 2);
            $table->decimal('total_expenses', 10, 2);
            $table->decimal('total_shared_expenses', 10, 2);
            $table->json('shares_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
