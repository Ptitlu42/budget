<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('type');
            $table->date('date');
            $table->boolean('locked')->default(false);
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('type');
            $table->date('date');
            $table->boolean('is_shared')->default(true);
            $table->boolean('locked')->default(false);
            $table->timestamps();
        });

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

        Schema::create('custom_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['income', 'expense']);
            $table->timestamps();
            $table->unique(['name', 'category']);
        });

        DB::table('custom_types')->insert([
            ['name' => 'salary', 'category' => 'income', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'aid', 'category' => 'income', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'other', 'category' => 'income', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rent', 'category' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'insurance', 'category' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'utilities', 'category' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'groceries', 'category' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'other', 'category' => 'expense', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_types');
        Schema::dropIfExists('history');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('incomes');
    }
};
