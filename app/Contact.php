<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function contact_type() {
        return $this->hasOne('App\ContactType');
    }
}
