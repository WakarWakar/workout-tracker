<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseDefinition extends Model
{
    protected $fillable = ['name', 'muscle_worked_id', 'category_id']; 

    public function workoutSets(){
        return $this->hasMany(WorkoutSet::class, 'exercise_definition_id');
    }

    public function muscleWorked()
    {
        return $this->belongsTo(MuscleWorked::class, 'muscle_worked_id');
    }

    public function exerciseCategory()
    {
        return $this->belongsTo(ExerciseCategory::class, 'category_id');
    }

}