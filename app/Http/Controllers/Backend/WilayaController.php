<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackendBaseController;

use App\Http\SaidTech\Repositories\WilayasRepository\WilayaRepository;
use App\Http\SaidTech\Repositories\ServicesRepository\ServiceRepository;

class WilayaController extends BackendBaseController
{
    /**
     * @var WilayaRepository
     */

    protected $repository;

    public function __construct(
        WilayaRepository $repository,
        ServiceRepository $serviceRepository
    )
    {
        $this->repository = $repository;
        $this->repositories['ServiceRepository'] = $serviceRepository;

        $this->middleware('superAdmin');

        $this->setRubricConfig('wilayas');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'list_wilayas'  => $this->repository->all(),
            'list_services' => $this->repositories['ServiceRepository']->all()
        ];

        return view($this->base_view . 'index', ['data' => $data]);
    }
    /**
     * Change price for a wilaya
     */
    public function changePrice(Request $request, $id) {
        $wilaya = $this->repository->find($id);
        $wilaya->price = $request->price;
        $status = $wilaya->save();

        if($status){
            return response()->json([
                'success' => true,
                'message' => trans('notifications.price_changed'),
                'newPrice' => $request->price
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => trans('notifications.error_occured')
            ]);
        }
    }

    /**
     * Set a wilaya (available/non available) for delivery
     */
    public function toggleAvailablity(Request $request, $id) {
        $wilaya = $this->repository->find($id);
        $wilaya->availability = $request->availability;
        $status = $wilaya->save();

        if($status){
            return response()->json([
                'success' => true,
                'message' => trans('notifications.availability_changed'),
                'newStatus' => $request->availability
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => trans('notifications.error_occured')
            ]);
        }
    }

    /**
     * Add service to wilaya
     */
    public function addService(Request $request, $id) {
        $wilaya = $this->repository->find($id);
        $status = $wilaya->services()->attach($request->service_id);

        return response()->json([
            'success' => true,
            'message' => trans('notifications.service_wilaya_added'),
        ]);
    }


    /**
     * Remove service from a wilaya
     */
    public function deleteService(Request $request, $id) {
        $wilaya = $this->repository->find($id);
        $wilaya->services()->detach($request->service_id);

        return response()->json([
            'success' => true,
            'message' => trans('notifications.service_wilaya_deleted')
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->back();
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
        return redirect()->back();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
