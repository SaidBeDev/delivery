<?php

namespace App\Http\SaidTech\Repositories\OrdersRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class OrderRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Order";
    }
}
