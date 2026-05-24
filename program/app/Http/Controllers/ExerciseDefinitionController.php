<?php

namespace App\Http\Controllers;

use App\Models\ExerciseDefinition;
use Illuminate\Http\Request;

class ExerciseDefinitionController extends Controller
{
    public function createExerciseDefinition(Request $request){
        $incomingFields = $request->validate([
            'name' => 'required',
            'muscles_worked' => 'required',
            'category' => 'required'
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']); # sanitize the name to prevent XSS attacks # ToDo check this security measure
        $incomingFields['muscles_worked'] = strip_tags($incomingFields['muscles_worked']); 
        $incomingFields['category'] = strip_tags($incomingFields['category']); 
        ExerciseDefinition::create($incomingFields);
        return redirect('/'); 
    }
}
