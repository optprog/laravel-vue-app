<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
   
    public function question_difficulty()
    {
        return $this->belongsTo('App\Question_difficulty');
    }

    public function choice()
    {
        return $this->hasMany('App\Choice');
    }

    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

}
