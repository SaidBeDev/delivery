<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    public function contact() {
        return $this->belongsTo('App\Contact', 'contact_type_id');
    }
}
