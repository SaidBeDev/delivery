<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;

use App\Http\SaidTech\Repositories\ConfigsRepository\ConfigRepository;
use App\Http\SaidTech\Repositories\ProfileTypesRepository\ProfileTypeRepository;

use jsValidator;

class ConfigController extends BackendBaseController
{
    /**
     * @var ConfigRepository
     */

    protected $repository;

    public function __construct(
        ConfigRepository $repository,
        ProfileTypeRepository $profileTypesRepository
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
            'validator'    => jsValidator::make($this->getConfigRules())
        ];

        return view($this->base_view . 'index', compact($data));
    }

    public function update(Request $request, $id) {
        $this->repository->update($request->validate($this->getConfigRules()), $id);
    }

    public function getConfigRules() {
        return [
            'name' => 'required|string|unique:configs',
            'content' => 'required|string'
        ];
    }
}
