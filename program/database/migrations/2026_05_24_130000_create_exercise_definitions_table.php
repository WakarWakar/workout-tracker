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
        $exerciseSeedData = [
            ['name' => 'bench press', 'muscles_worked' => 'chest, front delts, triceps', 'category' => 'front'],
            ['name' => 'overhead press', 'muscles_worked' => 'shoulders, triceps', 'category' => 'front'],
            ['name' => 'squat', 'muscles_worked' => 'quads, glutes, core', 'category' => 'legs'],
            ['name' => 'deadlift', 'muscles_worked' => 'hamstrings, glutes, back', 'category' => 'back'],
            ['name' => 'barbell row', 'muscles_worked' => 'lats, mid back, biceps', 'category' => 'back'],
            ['name' => 'biceps curl', 'muscles_worked' => 'biceps', 'category' => 'arms'],
            ['name' => 'triceps pushdown', 'muscles_worked' => 'triceps', 'category' => 'arms'],
        ];

        Schema::create('muscles_worked', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        foreach (collect($exerciseSeedData)->pluck('muscles_worked')->unique() as $musclesWorkedName) {
            DB::table('muscles_worked')->insert([
                'name' => $musclesWorkedName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (collect($exerciseSeedData)->pluck('category')->unique() as $categoryName) {
            DB::table('categories')->insert([
                'name' => $categoryName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::create('exercise_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('muscle_worked_id')->constrained('muscles_worked');
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();
        });

        foreach ($exerciseSeedData as $exerciseDefinition) {
            $muscleWorkedId = DB::table('muscles_worked')
                ->where('name', $exerciseDefinition['muscles_worked'])
                ->value('id');

            $categoryId = DB::table('categories')
                ->where('name', $exerciseDefinition['category'])
                ->value('id');

            DB::table('exercise_definitions')->insert([
                'name' => $exerciseDefinition['name'],
                'muscle_worked_id' => $muscleWorkedId,
                'category_id' => $categoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_definitions');
    }
};
