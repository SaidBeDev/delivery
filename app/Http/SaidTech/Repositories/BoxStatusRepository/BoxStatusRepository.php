<?php

namespace App\Http\SaidTech\Repositories\BoxStatusRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class BoxStatusRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\BoxStatus";
    }
}
