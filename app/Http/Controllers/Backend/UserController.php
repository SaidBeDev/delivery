<?php

namespace App\Http\Controllers\Backend;

use jsValidator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Controllers\Backend\BackendBaseController;
use App\Http\SaidTech\Repositories\UsersRepository\UserRepository;
use App\Http\SaidTech\Repositories\DairasRepository\DairaRepository;
use App\Http\SaidTech\Repositories\WilayasRepository\WilayaRepository;
use App\Http\SaidTech\Repositories\ServicesRepository\ServiceRepository;
use App\Http\SaidTech\Repositories\ProfileTypesRepository\ProfileTypeRepository;
use App\Http\SaidTech\Repositories\VehicleTypesRepository\VehicleTypeRepository;

use App\User;

class UserController extends BackendBaseController
{
    /**
     * @var UserRepository
     */

    protected $repository;

    public function __construct(
        UserRepository $repository,
        ProfileTypeRepository $profileTypesRepository,
        ServiceRepository $serviceRepository,
        WilayaRepository $wilayaRepository,
        DairaRepository $dairaRepository,
        VehicleTypeRepository $vehicleTypeRepository

    )
    {
        $this->repository = $repository;
        $this->repositories['ProfileTypesRepository'] = $profileTypesRepository;
        $this->repositories['VehicleTypesRepository'] = $vehicleTypeRepository;
        $this->repositories['ServiceRepository'] = $serviceRepository;
        $this->repositories['WilayaRepository'] = $wilayaRepository;
        $this->repositories['DairaRepository'] = $dairaRepository;

        $this->middleware('superAdmin');

        $this->setRubricConfig('users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = [
            'list_users'  => $this->repository->whereHas('profile_type', function(Builder $query){
                $query->where('name', '<>', 'superAdmin');
            })->with(['daira', 'profile_type', 'vehicle_type'])->all()
        ];

        return view($this->base_view . 'index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data = [
            'profile_types' => $this->repositories['ProfileTypesRepository']->scopeQuery(function($query) {
                return $query->where('name', '<>', 'superAdmin');
            })->all(),
            'vehicle_types' => $this->repositories['VehicleTypesRepository']->all(),
            'list_services' => $this->repositories['ServiceRepository']->all(),
            'list_wilayas'  => $this->repositories['WilayaRepository']->with('dairas')->all(),
            'list_dairas'  => $this->repositories['DairaRepository']->whereHas('wilaya', function(Builder $query){
                $query->where('availability',true);
            })->all(),
            'userValidator' => jsValidator::make($this->getUserRules()),
            'deliveryManValidator' => jsValidator::make($this->getDeliveryManRules()),
        ];


        return view($this->base_view . 'create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeProfile = $this->repositories['ProfileTypesRepository']->find($request->profile_type_id);

        switch($typeProfile->name) {
            case 'distributor':
                $user = $request->validate($this->getUserRules());

                $user['password'] = Hash::make($request->password);

                $this->repository->create($user);
                break;

            case 'deliveryMan':
                $user = $request->validate($this->getDeliveryManRules());

                $user['password'] = Hash::make($request->password);

                $this->repository->create($user);
                break;
        }

        return redirect()->route('admin.users.index')->with(['success' => true, 'message' => trans('notifications.user_created')]);
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
            'userValidator'        => jsValidator::make($this->getUserRules()),
            'deliveryManValidator' => jsValidator::make($this->getDeliveryManRules()),
            'user'                 => $this->repository->find($id),
            'profile_types' => $this->repositories['ProfileTypesRepository']->scopeQuery(function($query) {
                return $query->where('name', '<>', 'superAdmin');
            })->all(),
            'vehicle_types' => $this->repositories['VehicleTypesRepository']->all(),
            'list_services' => $this->repositories['ServiceRepository']->all(),
            'list_wilayas'  => $this->repositories['WilayaRepository']->with('dairas')->all(),
            'list_dairas'  => $this->repositories['DairaRepository']->whereHas('wilaya', function(Builder $query){
                $query->where('availability',true);
            })->all(),
        ];

        return view($this->base_view . 'edit', ['data' => $data]);
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
        $oldUser     = $this->repository->find($id);
        $typeProfile = $this->repositories['ProfileTypesRepository']->find($request->profile_type_id);

        switch($typeProfile->name) {
            case 'distributor':
                $user = $request->validate($this->getUserUpdateRules());

                if(!empty($request->password)){
                    $new_password = Hash::make($request->password);

                    if($request->password != $new_password) {
                        $user['password'] = $new_password;
                    }
                    else {
                        $user['password'] = $oldUser->password;
                    }
                }
                else {
                    $user['password'] = $oldUser->password;
                }

                $this->repository->update($user, $id);
                break;

            case 'deliveryMan':
                $user = $request->validate($this->getDeliveryManUpdateRules());

                if(!empty($request->password)){
                    $new_password = Hash::make($request->password);

                    if($request->password != $new_password) {
                        $user['password'] = $new_password;
                    }
                    else {
                        $user['password'] = $oldUser->password;
                    }
                }
                else {
                    $user['password'] = $oldUser->password;
                }


                $this->repository->update($user, $id);
                break;
        }

        return redirect()->route('admin.users.index')->with(['success' => true, 'message' => trans('notifications.user_updated')]);
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

    public function getUserRules() {
        return [
            'profile_type_id'=> 'required',
            'full_name'    => 'required',
            'username'     => 'required|unique:users',
            'email'        => 'nullable|email',
            'password'     => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'tel'          => 'required',
            'address'      => 'required',
            'service_id'   => 'required',
            'daira_id'     => 'required',
        ];
    }

    public function getDeliveryManRules() {
        return [
            'profile_type_id'=> 'required',
            'full_name'      => 'required',
            'username'       => 'required|unique:users',
            'email'          => 'nullable|email',
            'password'       => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'tel'            => 'required',
            'address'        => 'required',
            'service_id'     => 'required',
            'daira_id'      => 'required',
            'vehicle_type_id'=> 'required'
        ];
    }

    public function getUserUpdateRules() {
        return [
            'profile_type_id'=> 'required',
            'full_name'    => 'required',
            'username'     => 'required',
            'email'        => 'nullable|email',
            'password'     => 'nullable|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'tel'          => 'required',
            'address'      => 'required',
            'service_id'   => 'required',
            'daira_id'     => 'required',
        ];
    }

    public function getDeliveryManUpdateRules() {
        return [
            'profile_type_id'=> 'required',
            'full_name'      => 'required',
            'username'       => 'required',
            'email'          => 'nullable|email',
            'password'     => 'nullable|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'tel'            => 'required',
            'address'        => 'required',
            'service_id'     => 'required',
            'daira_id'      => 'required',
            'vehicle_type_id'=> 'required'
        ];
    }
}
