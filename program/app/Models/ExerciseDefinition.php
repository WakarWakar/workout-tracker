<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseDefinition extends Model
{
    # name (string), musclesWorked (string), category (string)  
    protected $fillable = ['name', 'muscles_worked', 'category']; 

    public function workoutSets(){
        return $this->hasMany(WorkoutSet::class, 'exercise_definition_id');
    }

}