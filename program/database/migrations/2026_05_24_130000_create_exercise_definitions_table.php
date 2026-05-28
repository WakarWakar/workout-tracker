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

        // collect and insert individual muscle names (split comma-separated lists)
        $allMuscles = collect($exerciseSeedData)
            ->pluck('muscles_worked')
            ->map(function ($s) { return collect(array_map('trim', explode(',', $s))); })
            ->flatten()
            ->unique();

        foreach ($allMuscles as $muscleName) {
            DB::table('muscles_worked')->insert([
                'name' => $muscleName,
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
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();
        });

        // pivot table for many-to-many between exercise_definitions and muscles_worked
        Schema::create('exercise_definition_muscle_worked', function (Blueprint $table) {
            $table->foreignId('exercise_definition_id')->constrained('exercise_definitions');
            $table->foreignId('muscle_worked_id')->constrained('muscles_worked');
            $table->primary(['exercise_definition_id', 'muscle_worked_id']);
        });

        // insert exercise_definitions and pivot records
        foreach ($exerciseSeedData as $exerciseDefinition) {
            $categoryId = DB::table('categories')
                ->where('name', $exerciseDefinition['category'])
                ->value('id');

            $exerciseDefinitionId = DB::table('exercise_definitions')->insertGetId([
                'name' => $exerciseDefinition['name'],
                'category_id' => $categoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (array_map('trim', explode(',', $exerciseDefinition['muscles_worked'])) as $muscleName) {
                $muscleId = DB::table('muscles_worked')
                    ->where('name', $muscleName)
                    ->value('id');

                if ($muscleId) {
                    DB::table('exercise_definition_muscle_worked')->insert([
                        'exercise_definition_id' => $exerciseDefinitionId,
                        'muscle_worked_id' => $muscleId,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_definition_muscle_worked');
        Schema::dropIfExists('exercise_definitions');
        Schema::dropIfExists('muscles_worked');
        Schema::dropIfExists('categories');
    }
};
