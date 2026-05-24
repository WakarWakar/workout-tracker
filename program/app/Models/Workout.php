<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    # name (string), date (date), userId (int)
    # userId is a foreign key that references the id of a User
    protected $fillable = ['name', 'date', 'user_id'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    # users make their own workouts, so we need to link the workout to the user who created it   
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function workoutSets(){ 
        return $this->hasMany(WorkoutSet::class, 'workout_id');
    }
}