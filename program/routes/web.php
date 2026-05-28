<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MuscleWorkedController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseDefinitionController;
use App\Models\ExerciseCategory;
use App\Models\ExerciseDefinition;
use App\Models\MuscleWorked;

Route::get('/', function () {
    $allWorkouts = [];
    $exerciseDefinitions = ExerciseDefinition::with(['musclesWorked', 'exerciseCategory'])->orderBy('name')->get();
    $muscleWorkedOptions = MuscleWorked::orderBy('name')->get();
    $categoryOptions = ExerciseCategory::orderBy('name')->get();

    if (auth()->check()) {
        $allWorkouts = auth()->user()->userWorkouts()->with(['workoutSets.exerciseDefinition'])->latest()->get();
    }

    return view('home', [
        'workouts' => $allWorkouts,
        'exerciseDefinitions' => $exerciseDefinitions,
        'muscleWorkedOptions' => $muscleWorkedOptions,
        'categoryOptions' => $categoryOptions,
    ]);
});


Route::post('/register' , [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::post('/create-workout', [WorkoutController::class, 'createWorkout']);
    Route::get('/edit-workout/{workout}', [WorkoutController::class, 'showEditScreen']);
    Route::put('/edit-workout/{workout}', [WorkoutController::class, 'updateWorkout']);
    Route::delete('/delete-workout/{workout}', [WorkoutController::class, 'deleteWorkout']);

    Route::post('/muscles-worked', [MuscleWorkedController::class, 'create']);
    Route::delete('/muscles-worked/{muscleWorked}', [MuscleWorkedController::class, 'delete']);
    Route::post('/categories', [CategoryController::class, 'create']);
    Route::delete('/categories/{category}', [CategoryController::class, 'delete']);

    Route::post('/exercise-definitions', [ExerciseDefinitionController::class, 'createExerciseDefinition']);
    Route::put('/exercise-definitions/{exerciseDefinition}', [ExerciseDefinitionController::class, 'updateExerciseDefinition']);
    Route::delete('/exercise-definitions/{exerciseDefinition}', [ExerciseDefinitionController::class, 'deleteExerciseDefinition']);
});
