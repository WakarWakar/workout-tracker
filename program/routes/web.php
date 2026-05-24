<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use App\Models\ExerciseDefinition;

Route::get('/', function () {
    $allWorkouts = [];
    $exerciseDefinitions = ExerciseDefinition::orderBy('category')->orderBy('name')->get();

    if (auth()->check()) {
        $allWorkouts = auth()->user()->userWorkouts()->with(['workoutSets.exerciseDefinition'])->latest()->get();
    }

    return view('home', [
        'workouts' => $allWorkouts,
        'exerciseDefinitions' => $exerciseDefinitions,
    ]);
});


Route::post('/register' , [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/create-workout', [WorkoutController::class, 'createWorkout']);
Route::get('/edit-workout/{workout}', [WorkoutController::class, 'showEditScreen']);
Route::put('/edit-workout/{workout}', [WorkoutController::class, 'updateWorkout']);
Route::delete('/delete-workout/{workout}', [WorkoutController::class, 'deleteWorkout']);
