<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuscleWorked extends Model
{
    protected $table = 'muscles_worked';

    protected $fillable = ['name'];
}