<?php

namespace App\Http\Controllers;

use App\Models\ExerciseDefinition;
use App\Models\MuscleWorked;
use Illuminate\Http\Request;

class MuscleWorkedController extends Controller
{
    private function ensureAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/');
        }

        return null;
    }

    private function success(string $message)
    {
        return redirect('/')->with('status', $message);
    }

    private function failure(string $message)
    {
        return redirect('/')->withErrors(['submission' => $message])->withInput();
    }

    public function create(Request $request)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

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

    public function delete(MuscleWorked $muscleWorked)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        if ($muscleWorked->exerciseDefinitions()->exists()) {
            return $this->failure('Cannot delete muscles worked "' . $muscleWorked->name . '" because it is used by an exercise definition.');
        }

        $muscleWorkedName = $muscleWorked->name;
        $muscleWorked->delete();

        return $this->success('Muscles worked "' . $muscleWorkedName . '" deleted successfully.');
    }
}