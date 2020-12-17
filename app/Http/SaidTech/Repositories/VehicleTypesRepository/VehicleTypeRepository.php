<?php

namespace App\Http\SaidTech\Repositories\VehicleTypesRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class VehicleTypeRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\VehicleType";
    }
}
