<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSet;
use Illuminate\Http\Request;

class WorkoutSetController extends Controller
{
    public function createWorkoutSet(Request $request){

        return redirect('/'); 
    }
}