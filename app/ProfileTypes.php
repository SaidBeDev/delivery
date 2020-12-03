<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileTypes extends Model
{
    //
    public function user() {
        return $this->belongsTo('App\User', 'profile_type_id');
    }
}
