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
        $data = [
            'list_services' => $this->repository->all()
        ];

        return view('backend.rubrics.services.index', compact($data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'validator' => jsValidator::make($this->getServiceRules())
        ];

        return view('backend.rubrics.services.create', compact($data));
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
        $data = [
            'service' => $this->repository->find($id)
        ];

        return view('backend.rubrics.services.edit', compact($data));
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
