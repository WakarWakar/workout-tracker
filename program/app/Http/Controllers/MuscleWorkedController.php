<?php

namespace App\Http\Controllers;

use App\Models\ExerciseDefinition;
use App\Models\MuscleWorked;
use Illuminate\Http\Request;

class MuscleWorkedController extends Controller
{
    private function success(string $message)
    {
        return back()->with('status', $message);
    }

    private function failure(string $message)
    {
        return back()->withErrors(['submission' => $message])->withInput();
    }

    public function create(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);

        if (MuscleWorked::where('name', $incomingFields['name'])->exists()) {
            return $this->failure('The muscles worked name "' . $incomingFields['name'] . '" already exists.');
        }

        MuscleWorked::create($incomingFields);

        return $this->success('Muscles worked "' . $incomingFields['name'] . '" created successfully.');
    }

    public function update(Request $request, MuscleWorked $muscleWorked)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']); # sanitize input to prevent XSS attacks

        if (MuscleWorked::where('name', $incomingFields['name'])->where('id', '!=', $muscleWorked->id)->exists()) {
            return $this->failure('The muscles worked name "' . $incomingFields['name'] . '" already exists.');
        }

        $muscleWorked->update($incomingFields);

        return $this->success('Muscles worked "' . $incomingFields['name'] . '" updated successfully.');
    }

    public function delete(MuscleWorked $muscleWorked)
    {
        if ($muscleWorked->exerciseDefinitions()->exists()) {
            return $this->failure('Cannot delete muscles worked "' . $muscleWorked->name . '" because it is used by an exercise definition.');
        }

        $muscleWorkedName = $muscleWorked->name;
        $muscleWorked->delete();

        return $this->success('Muscles worked "' . $muscleWorkedName . '" deleted successfully.');
    }
}