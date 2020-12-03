<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daira extends Model
{
    //
    public function commune() {
        return $this->hasMany('App\Commune', 'daira_id');
    }

    public function wilaya() {
        return $this->belongsTo('App\Wilaya');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
