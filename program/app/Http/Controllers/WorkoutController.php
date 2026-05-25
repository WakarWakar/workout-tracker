<?php

namespace App\Http\Controllers;

use App\Models\ExerciseCategory;
use App\Models\ExerciseDefinition;
use App\Models\MuscleWorked;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index(){
        $allWorkouts = [];
        if (auth()->check()) {
            $allWorkouts = auth()->user()->userWorkouts()->with(['workoutSets.exerciseDefinition'])->latest()->get();
        }

        return view('workouts', [
            'workouts' => $allWorkouts,
            'exerciseDefinitions' => ExerciseDefinition::with(['muscleWorked', 'exerciseCategory'])->orderBy('name')->get(),
            'muscleWorkedOptions' => MuscleWorked::orderBy('name')->get(),
            'categoryOptions' => ExerciseCategory::orderBy('name')->get(),
        ]);
    }

    public function createWorkout(Request $request){
        if (auth()->user()->isAdmin()) {
            return redirect('/')->withErrors(['submission' => 'Admins cannot create workouts.']);
        }

        $incomingFields = $request->validate([
            'name' => 'required',
            'date' => 'required|date',
            'workout_sets' => 'required|array|min:1',
            'workout_sets.*.exercise_definition_id' => 'required|exists:exercise_definitions,id',
            'workout_sets.*.weight' => 'required|numeric|min:0',
            'workout_sets.*.reps' => 'required|integer|min:1',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']); # sanitize the name to prevent XSS attacks # ToDo check this security measure
        $incomingFields['date'] = strip_tags($incomingFields['date']);
        $incomingFields['user_id'] = auth()->id();
        $workout = Workout::create([
            'name' => $incomingFields['name'],
            'date' => $incomingFields['date'],
            'user_id' => $incomingFields['user_id'],
        ]);

        $this->syncWorkoutSets($workout, $incomingFields['workout_sets']);
        return redirect('/')->with('status', 'Workout created successfully.'); 
    }

    public function showEditScreen(Workout $workout){
        if (auth()->user()->isAdmin()) {
            return redirect('/')->withErrors(['submission' => 'Admins cannot edit workouts.']);
        }

        if (auth()->user()->id !== $workout->user_id) {
            return redirect('/')->withErrors(['submission' => 'You can only edit your own workout.']);
        }

        $workout->load('workoutSets.exerciseDefinition');

        return view('edit-workout', [
            'workout' => $workout,
            'exerciseDefinitions' => ExerciseDefinition::with(['muscleWorked', 'exerciseCategory'])->orderBy('name')->get(),
            'muscleWorkedOptions' => MuscleWorked::orderBy('name')->get(),
            'categoryOptions' => ExerciseCategory::orderBy('name')->get(),
        ]);
    }

    public function updateWorkout(Workout $workout, Request $request){
        if (auth()->user()->isAdmin()) {
            return redirect('/');
        }

        if (auth()->user()->id !== $workout->user_id) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'name' => 'required',
            'date' => 'required|date',
            'workout_sets' => 'required|array|min:1',
            'workout_sets.*.exercise_definition_id' => 'required|exists:exercise_definitions,id',
            'workout_sets.*.weight' => 'required|numeric|min:0',
            'workout_sets.*.reps' => 'required|integer|min:1',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);
        $incomingFields['date'] = strip_tags($incomingFields['date']);

        $workout->update([
            'name' => $incomingFields['name'],
            'date' => $incomingFields['date'],
        ]);

        $this->syncWorkoutSets($workout, $incomingFields['workout_sets']);
        return redirect('/')->with('status', 'Workout updated successfully.');
    }

    public function deleteWorkout(Workout $workout){
        if (auth()->user()->isAdmin()) {
            return redirect('/')->withErrors(['submission' => 'Admins cannot delete workouts.']);
        }

        if (auth()->user()->id === $workout->user_id) { # ToDo least privilege check to ensure that only the owner of the workout can delete it
            $workout->delete();
            return redirect('/')->with('status', 'Workout deleted successfully.');
        }

        return redirect('/')->withErrors(['submission' => 'You can only delete your own workout.']);
    }

    private function syncWorkoutSets(Workout $workout, array $workoutSets): void
    {
        $workout->workoutSets()->delete();

        foreach ($workoutSets as $workoutSet) {
            $workout->workoutSets()->create([
                'exercise_definition_id' => $workoutSet['exercise_definition_id'],
                'weight' => $workoutSet['weight'],
                'reps' => $workoutSet['reps'],
            ]);
        }
    }
}