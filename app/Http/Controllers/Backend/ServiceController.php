<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;

use App\Http\SaidTech\Repositories\ServicesRepository\ServiceRepository;

class ServiceController extends BackendBaseController
{
    /**
     * @var ServiceRepository
     */

    protected $repository;

    public function __construct(
        ServiceRepository $repository
    )
    {
        $this->repository = $repository;

        $this->middleware('superAdmin');

        $this->setRubricConfig('services');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $info = [
            'title' => $this->title
        ];


        $data = [
            'list_services' => $this->repository->all()
        ];

        return view($this->base_view . 'index', ['data' => $data, 'info' => $info]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $info = [
            'title' => $this->title
        ];

        $data = [
            'validator' => jsValidator::make($this->getServiceRules())
        ];

        return view($this->base_view . 'create', ['data' => $data, 'info' => $info]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $newService = $request->validate($this->getServiceRules());

        $this->repository->create($newService);

        return redirect()->back()->with(['success' => trans('notifications.service_added')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = [
            'title' => $this->title
        ];

        $data = [
            'service' => $this->repository->find($id)
        ];

        return view($this->base_view . 'edit', ['data' => $data, 'info' => $info]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $newService = $request->validate($this->getServiceRules());

        $this->repository->update($newService, $id);

        return redirect()->back()->with(['success' => trans('notifications.service_updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function getServiceRules() {
        return [
            'name' => 'required'
        ];
    }
}
