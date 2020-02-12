<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function test_type()
    {
        return $this->belongsTo('App\Test_type');
    }

    public function test_question()
    {
        return $this->hasMany('App\Test_question');
    }
}
