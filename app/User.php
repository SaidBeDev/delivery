<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'username', 'email', 'password', 'tel', 'address', 'profile_type_id', 'service_id', 'daira_id', 'vehicle_type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile_type() {
        return $this->hasOne('App\ProfileTypes', 'id', 'profile_type_id');
    }

    public function service() {
        return $this->hasOne('App\Service');
    }

    public function vehicle_type() {
        return $this->hasOne('App\VehicleType', 'id', 'vehicle_type_id');
    }

    public function daira() {
        return $this->hasOne('App\Daira', 'id', 'daira_id');
    }

    public function boxes() {
        return $this->hasMany('App\Box');
    }

}
