<?php

namespace App\Http\SaidTech\Repositories\BoxesRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class BoxRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Box";
    }
}
