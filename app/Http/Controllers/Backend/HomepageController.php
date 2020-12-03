<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller\Backend\BackendBaseController;

use App\Http\SaidTech\Repositories\WilayasRepository\WilayaRepository;

class HomepageController extends Controller
{
    /**
     * @var WilayaRepository
     */
    protected $repository;

    public function __construct(WilayaRepository $repository){
        $this->repository = $repository;
    }

    public function index() {
        $data = $this->repository->paginate(10)->first();
        return dd($data);
    }
}
