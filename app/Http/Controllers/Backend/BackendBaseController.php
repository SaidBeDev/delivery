<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackendBaseController extends Controller
{
    // Initializing variables
    public $rubric_uri = null;
    public $rubric_name = null;
    protected $base_view = null;
    protected $title = null;
    public $data = [];

    protected $where = null;
    protected $value = null;
    protected $model = [];
    protected $repositories = [];


    public function setRubricConfig($rubric_name){

        $this->base_view   = 'backend.rubrics.' . $rubric_name . '.';
        $this->rubric_name = $rubric_name;
        $this->rubric_uri  = trans('routes.' . $rubric_name);
        $this->title       = trans('menu.' . $rubric_name);
    }
}
