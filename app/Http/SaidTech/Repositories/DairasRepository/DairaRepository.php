<?php

namespace App\Http\SaidTech\Repositories\DairasRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class DairaRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Daira";
    }
}
