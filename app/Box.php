<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    //
    public function daira() {
        return $this->hasOne('App\Daira', 'daira_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function assigned_user() {
        return $this->belongsTo('App\User', 'assigned_user_id');
    }
}
