<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question_difficulty extends Model
{
   
    public function question()
    {
        return $this->hasMany('App\Question');
    }


}
