<?php

namespace App\Http\SaidTech\Repositories\PostsRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class PostRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Post";
    }
}
