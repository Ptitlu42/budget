<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('custom_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['income', 'expense']);
            $table->timestamps();
            $table->unique(['name', 'category']);
        });

        // Insérer les types par défaut
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_types');
    }
};
