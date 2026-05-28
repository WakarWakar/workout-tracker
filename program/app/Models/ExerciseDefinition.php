<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseDefinition extends Model
{
    protected $fillable = ['name', 'category_id']; 

    public function workoutSets(){
        return $this->hasMany(WorkoutSet::class, 'exercise_definition_id');
    }

    public function musclesWorked()
    {
        return $this->belongsToMany(MuscleWorked::class, 'exercise_definition_muscle_worked', 'exercise_definition_id', 'muscle_worked_id');
    }

    public function exerciseCategory()
    {
        return $this->belongsTo(ExerciseCategory::class, 'category_id');
    }

}