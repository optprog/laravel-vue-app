<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    public function lesson()
    {
        return $this->hasMany('App\Lesson');
    }

    public function test()
    {
        return $this->hasMany('App\Test');
    }


    // public function video()
    // {
    //     return $this->hasMany('App\Video');
    // }

    public function user_course()
    {
        return $this->hasMany('App\User_course');
    }

}
//$x = App\Course::find(1)->with('lesson.Article')->get()
//This command in tinker retrieves the Course with its lessons and with articels of each lesson where with() method get the table names as input

//another syntax could be $x = App\Course::find(1)
//$x->with('lesson.Article')->get()
//This will produce the same result