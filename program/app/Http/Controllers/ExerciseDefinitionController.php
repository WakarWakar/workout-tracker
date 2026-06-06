<?php

namespace App\Http\Controllers;

use App\Models\ExerciseCategory;
use App\Models\ExerciseDefinition;
use App\Models\MuscleWorked;
use Illuminate\Http\Request;

class ExerciseDefinitionController extends Controller
{
    private function ensureAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/');
        }

        return null;
    }

    public function createExerciseDefinition(Request $request){
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $incomingFields = $request->validate([
            'name' => 'required',
            'muscle_worked_ids' => 'required|array',
            'muscle_worked_ids.*' => 'exists:muscles_worked,id',
            'category_id' => 'required|exists:categories,id'
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);

        if (ExerciseDefinition::where('name', $incomingFields['name'])->exists()) {
            return back()
                ->withErrors(['submission' => 'The exercise definition name "' . $incomingFields['name'] . '" already exists.'])
                ->withInput();
        }

        $exercise = ExerciseDefinition::create([
            'name' => $incomingFields['name'],
            'category_id' => $incomingFields['category_id'],
        ]);

        $exercise->musclesWorked()->sync($incomingFields['muscle_worked_ids']);

        return back()->with('status', 'Exercise definition "' . $incomingFields['name'] . '" created successfully.'); 
    }

    public function updateExerciseDefinition(ExerciseDefinition $exerciseDefinition, Request $request)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $incomingFields = $request->validate([
            'name' => 'required',
            'muscle_worked_ids' => 'required|array',
            'muscle_worked_ids.*' => 'exists:muscles_worked,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);

        if (ExerciseDefinition::where('name', $incomingFields['name'])
            ->where('id', '!=', $exerciseDefinition->id)
            ->exists()) {
            return back()
                ->withErrors(['submission' => 'The exercise definition name "' . $incomingFields['name'] . '" already exists.'])
                ->withInput();
        }

        $exerciseDefinition->update([
            'name' => $incomingFields['name'],
            'category_id' => $incomingFields['category_id'],
        ]);

        $exerciseDefinition->musclesWorked()->sync($incomingFields['muscle_worked_ids']);

        return back()->with('status', 'Exercise definition "' . $incomingFields['name'] . '" updated successfully.');
    }

    public function deleteExerciseDefinition(ExerciseDefinition $exerciseDefinition)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $exerciseName = $exerciseDefinition->name;
        $exerciseDefinition->delete();
        return back()->with('status', 'Exercise definition "' . $exerciseName . '" deleted successfully.');
    }
}
