<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daira extends Model
{
    //
    public function commune() {
        return $this->hasMany('App\Commune');
    }

    public function wilaya() {
        return $this->belongsTo('App\Wilaya');
    }

    /* public function user() {
        return $this->belongsTo('App\User', 'daira_id');
    } */
}
