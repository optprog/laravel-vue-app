<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test_question extends Model
{
    
    
    public function test()
    {
        return $this->belongsTo('App\Test');
    }
 
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

    public function question()
    {
        return $this->belongsTo('App\Question');
    }
    public function test_type()
    {
        return $this->belongsTo('App\Test_type');
    }

}
