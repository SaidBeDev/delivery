<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    //
    public function daira() {
        return $this->belongsTo('App\Daira', 'daira_id');
    }

    public function wilaya() {
        return $this->belongsTo('App\Wilaya', 'wilaya_id');
    }
}
