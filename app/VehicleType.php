<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    //

    public function user() {
        return $this->belongsTo('App\VehicleType', 'vehicle_type_id');
    }
}
