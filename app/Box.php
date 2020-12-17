<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = ['full_name', 'tel', 'code', 'address', 'note', 'price', 'total_price', 'user_id', 'service_id', 'daira_id', 'box_status_id', 'assigned_user_id', 'is_received', 'is_returned'];
    //
    public function box_status() {
        return $this->hasOne('App\BoxStatus', 'id', 'box_status_id');
    }

    public function daira() {
        return $this->hasOne('App\Daira', 'id', 'daira_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function assigned_user() {
        return $this->belongsTo('App\User', 'assigned_user_id');
    }
}
