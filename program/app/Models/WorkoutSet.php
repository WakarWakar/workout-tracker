<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSet extends Model
{
    # weight (float), reps (int), workoutId (int), exerciseDefinitionId (int)
    protected $fillable = ['workout_id', 'exercise_definition_id', 'weight', 'reps']; 

    public function workout(){
        return $this->belongsTo(Workout::class, 'workout_id');
    }
    
    # WorkoutSet exercise_definition_id is a foreign key that references the id of an ExerciseDefinition
    public function exerciseDefinition(){
        return $this->belongsTo(ExerciseDefinition::class, 'exercise_definition_id');
    }
   
}