<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    public function user() {
        return $this->belongsTo('App\User', 'service_id');
    }

    public function wilayas() {
        return $this->morphedByMany('App\Wilaya', 'serviceable');
    }
}
