<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    
    // public function User_test_answer()
    // {
    //     return $this->hasOne('App\User_test_answer');
    // }


}
