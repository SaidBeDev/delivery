<?php

namespace App\Http\SaidTech\Repositories\ConfigsRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class ConfigRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Config";
    }
}
