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
        $info = [
            'title' => $this->title
        ];

        $data = [
            'list_configs' => $this->repository->all(),
            'validator'    => jsValidator::make($this->getConfigRules())
        ];

        return view($this->base_view . 'index', ['data' => $data, 'info' => $info]);
    }

    public function update(Request $request, $id) {
        $config = $this->repository->find($id);
        $config->content = $request->content;
        $config->save();

        return response()->json([
            'success' => true,
            'message' => trans('notifications.config_updated'),
        ]);
    }

    public function getConfigRules() {
        return [
            'name' => 'required|string|unique:configs',
            'content' => 'required|string'
        ];
    }
}
