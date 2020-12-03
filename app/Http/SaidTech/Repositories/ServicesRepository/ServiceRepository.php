<?php

namespace App\Http\SaidTech\Repositories\ServicesRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class ServiceRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Service";
    }
}
