<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuscleWorked extends Model
{
    protected $table = 'muscles_worked';

    protected $fillable = ['name'];

    public function exerciseDefinitions()
    {
        return $this->belongsToMany(ExerciseDefinition::class, 'exercise_definition_muscle_worked', 'muscle_worked_id', 'exercise_definition_id');
    }
}