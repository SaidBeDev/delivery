<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{
    /**
     * To delete a service from a wilaya use $wilaya->services()->detach(array of ids or just id)
     *  To add : $wilaya->services()->attach(array of ids or just id)
     **/

    public $timestamps = false;

    public function daira() {
        return $this->hasMany('App\Daira', 'daira_id');
    }

    public function services() {
        return $this->morphToMany('App\Service', 'serviceable');
    }
}
