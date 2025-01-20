<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('history', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->dropColumn([
                'incomes_data',
                'expenses_data',
                'total_incomes',
                'total_expenses',
                'total_shared_expenses',
                'shares_data'
            ]);
            $table->json('data');
        });
    }

    public function down(): void
    {
        Schema::table('history', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['group_id']);
            $table->dropColumn(['user_id', 'group_id', 'data']);
            $table->json('incomes_data');
            $table->json('expenses_data');
            $table->decimal('total_incomes', 10, 2);
            $table->decimal('total_expenses', 10, 2);
            $table->decimal('total_shared_expenses', 10, 2);
            $table->json('shares_data');
        });
    }
};
