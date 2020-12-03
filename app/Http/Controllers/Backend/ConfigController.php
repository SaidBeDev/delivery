<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\SaidTech\Repositories\ConfigsRepository\ConfigRepository;

class ConfigController extends Controller
{
    /**
     * @var ConfigRepository
     */

    protected $repository;

    public function __construct(
        ConfigRepository $repository
    )
    {
        $this->repository = $repository;
        $this->repositories['ProfileTypesRepository'] = $profileTypesRepository;

        $this->middleware('superAdmin');

        $this->setRubricConfig('configs');
    }

    public function index() {
        $data = [
            'list_configs' => $this->repository->all(),
        ];

        return view($this->base_view . 'index', compact($data));
    }

    public function edit($id) {

    }
}
