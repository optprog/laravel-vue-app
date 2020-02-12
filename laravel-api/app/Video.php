<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

  
}

