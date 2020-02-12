<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_test_result extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function test()
    {
        return $this->belongsTo('App\Test');
    }

}
