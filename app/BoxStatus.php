<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoxStatus extends Model
{
    public function box() {
        return$this->belongsTo('App\Box', 'box_status_id');
    }
}
