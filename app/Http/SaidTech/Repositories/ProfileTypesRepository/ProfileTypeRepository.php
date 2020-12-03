<?php

namespace App\Http\SaidTech\Repositories\ProfileTypesRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class ProfileTypeRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\ProfileTypes";
    }
}
