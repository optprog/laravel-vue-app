<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{


    public function article()
    {
        return $this->hasMany('App\Article');
    }

    public function video()
    {
        return $this->hasMany('App\Video');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }
}
