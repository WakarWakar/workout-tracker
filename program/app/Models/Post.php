<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = ['title', 'body', 'user_id']; # allow mass assignment for these fields

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
