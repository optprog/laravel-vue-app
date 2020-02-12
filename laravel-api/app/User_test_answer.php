<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_test_answer extends Model
{
   
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function test()
    {
        return $this->belongsTo('App\Test');
    }

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function choice()
    {
        return $this->belongsTo('App\Choice');
    }
    
}
