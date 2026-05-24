<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exercise_definitions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->text('muscles_worked');
            $table->string('category');
        });

        DB::table('exercise_definitions')->insert([
            ['name' => 'bench press', 'muscles_worked' => 'chest, front delts, triceps', 'category' => 'front', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'overhead press', 'muscles_worked' => 'shoulders, triceps', 'category' => 'front', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'squat', 'muscles_worked' => 'quads, glutes, core', 'category' => 'legs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'deadlift', 'muscles_worked' => 'hamstrings, glutes, back', 'category' => 'back', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'barbell row', 'muscles_worked' => 'lats, mid back, biceps', 'category' => 'back', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'biceps curl', 'muscles_worked' => 'biceps', 'category' => 'arms', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'triceps pushdown', 'muscles_worked' => 'triceps', 'category' => 'arms', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_definitions');
    }
};
