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
    // '/' is the user/guest home; admins belong on '/admin'.
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect('/admin');
    }

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
// Provide a named GET login route so auth middleware can redirect unauthenticated users.
// Otherwise you get error when you try to reach admin url without login credential
// # ToDo: make urls that are locked behind login, redirect to login page by default.
// # ToDo: when reaching url that doesn't exist, redirect to login by default (do this later).
Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::post('/create-workout', [WorkoutController::class, 'createWorkout']);
});

// Ownership is enforced via WorkoutPolicy (abort 404 for non-owners, including admins),
// so these don't need the role:user middleware.
Route::middleware('auth')->group(function () {
    Route::get('/edit-workout/{workout}', [WorkoutController::class, 'showEditScreen']);
    Route::put('/edit-workout/{workout}', [WorkoutController::class, 'updateWorkout']);
    Route::delete('/delete-workout/{workout}', [WorkoutController::class, 'deleteWorkout']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/muscles-worked', [MuscleWorkedController::class, 'create']);
    Route::put('/muscles-worked/{muscleWorked}', [MuscleWorkedController::class, 'update']);
    Route::delete('/muscles-worked/{muscleWorked}', [MuscleWorkedController::class, 'delete']);

    Route::post('/categories', [CategoryController::class, 'create']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'delete']);

    Route::post('/exercise-definitions', [ExerciseDefinitionController::class, 'createExerciseDefinition']);
    Route::put('/exercise-definitions/{exerciseDefinition}', [ExerciseDefinitionController::class, 'updateExerciseDefinition']);
    Route::delete('/exercise-definitions/{exerciseDefinition}', [ExerciseDefinitionController::class, 'deleteExerciseDefinition']);

    Route::get('/admin', function () {
        $exerciseDefinitions = ExerciseDefinition::with(['musclesWorked', 'exerciseCategory'])->orderBy('name')->get();
        $muscleWorkedOptions = MuscleWorked::orderBy('name')->get();
        $categoryOptions = ExerciseCategory::orderBy('name')->get();

        return view('admin', [
            'exerciseDefinitions' => $exerciseDefinitions,
            'muscleWorkedOptions' => $muscleWorkedOptions,
            'categoryOptions' => $categoryOptions,
        ]);
    });
});
