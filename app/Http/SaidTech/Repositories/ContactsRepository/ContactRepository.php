<?php

namespace App\Http\SaidTech\Repositories\ContactsRepository;

use Prettus\Repository\Eloquent\BaseRepository;

class ContactRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Contact";
    }
}
