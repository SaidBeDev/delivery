<?php

namespace App\Http\Controllers\Backend;

use jsValidator;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

use App\Http\SaidTech\Repositories\UsersRepository\UserRepository;
use App\Http\SaidTech\Repositories\ProfileTypesRepository\ProfileTypeRepository;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */

    protected $repository;

    public function __construct(
        UserRepository $repository,
        ProfileTypeRepository $profileTypesRepository
    )
    {
        $this->repository = $repository;
        $this->repositories['ProfileTypesRepository'] = $profileTypesRepository;

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
            })
        ];

        return view($this->base_view . 'index', compact($data));
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
            'profile_types' => $this->repositories['ProfileTypesRepository']->findWhere(['name', '<>', 'superAdmin']),
            'userValidator' => jsValidator::make($this->getUserRules()),
            'deliveryManValidator' => jsValidator::make($this->getDeliveryManRules()),
        ];

        return view($this->base_view . 'create', compact($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeProfile = $this->repositories['ProfileTypesRepository']->find($request->profile_type);

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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            'user'                 => $this->repository->find($id)
        ];

        return view($this->base_view . 'edit', compact($data));
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
        $typeProfile = $this->repositories['ProfileTypesRepository']->find($request->profile_type);

        switch($typeProfile->name) {
            case 'distributor':
                $user = $request->validate($this->getUserRules());

                $this->repository->update($user, $id);
                break;

            case 'deliveryMan':
                $user = $request->validate($this->getDeliveryManRules());

                $this->repository->update($user, $id);
                break;
        }
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
            'full_name'    => 'required',
            'username'     => 'required|unique:users',
            'email'        => 'required|unique:users|email',
            'password'     => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$',
            'tel'          => 'required',
            'address'      => 'required',
            'service_id'   => 'required',
            'wilaya_id'    => 'required',
        ];
    }

    public function getDeliveryManRules() {
        return [
            'full_name'      => 'required',
            'username'       => 'required|unique:users',
            'email'          => 'required|unique:users|email',
            'password'       => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$',
            'tel'            => 'required',
            'address'        => 'required',
            'service_id'     => 'required',
            'wilaya_id'      => 'required',
            'vehicle_type_id'=> 'required'
        ];
    }
}
