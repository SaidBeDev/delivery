<?php

namespace App\Http\Controllers\Backend;

use App\Daira;
use App\Wilaya;

use jsValidator;
use Dirape\Token\Token;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Backend\BackendBaseController;
use App\Http\SaidTech\Repositories\BoxesRepository\BoxRepository;
use App\Http\SaidTech\Repositories\UsersRepository\UserRepository;
use App\Http\SaidTech\Repositories\DairasRepository\DairaRepository;
use App\Http\SaidTech\Repositories\ConfigsRepository\ConfigRepository;
use App\Http\SaidTech\Repositories\WilayasRepository\WilayaRepository;
use App\Http\SaidTech\Repositories\ServicesRepository\ServiceRepository;
use App\Http\SaidTech\Repositories\BoxStatusRepository\BoxStatusRepository;

class BoxController extends BackendBaseController
{
    /**
     * @var BoxRepository
     */

    protected $repository;

    public function __construct(
        BoxRepository $repository,
        UserRepository $userRepository,
        ConfigRepository $configRepository,
        BoxStatusRepository $boxStatusRepository,
        ServiceRepository $serviceRepository,
        WilayaRepository $wilayaRepository,
        DairaRepository $dairaRepository
    )
    {
        $this->repository = $repository;
        $this->repositories['UserRepository'] = $userRepository;
        $this->repositories['ConfigRepository'] = $configRepository;
        $this->repositories['BoxStatusRepository'] = $boxStatusRepository;
        $this->repositories['ServiceRepository'] = $serviceRepository;
        $this->repositories['WilayaRepository'] = $wilayaRepository;
        $this->repositories['DairaRepository'] = $dairaRepository;


        $this->middleware('user');

        $this->setRubricConfig('boxes');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        if(!empty(Auth::user())) {
            $user = $this->repositories['UsersRepository']->find(Auth::id());

            switch($user->profile_type->name) {
                case 'superAdmin':
                    $data = [
                        'boxes'      => $this->repository->all(),
                        'box_status' => $this->repositories['BoxStatusRepository']->all(),
                        'list_delivrers' => $this->repositories['UsersRepository']->whereHas('profile_type', function(Builder $query){
                            $query->where('name', '=', 'deliveryMan');
                        })
                    ];
                break;

                case 'deliveryMan':
                    $data = [
                        'boxes'      => $this->repository->findWhere(['assigned_user_id' => Auth::id()]),
                        'box_status' => $this->repositories['BoxStatusRepository']->all(),
                    ];
                break;

                case 'distributor':
                    $data = [
                        'boxes'      => $this->repository->findWhere(['user_id' => Auth::id()]),
                        'box_status' => $this->repositories['BoxStatusRepository']->all(),
                    ];
                break;
            }
        }

        return view($this->base_view . 'index', compact($data));
    }

    /**
     * Change status of a box
     */
    public function changeStatus($id, $newStatusId) {

        $this->repository->find($id)->update(['box_status_id' => $newStatusId]);

        return response()->json([
            'success' => true,
            'message' => trans('notifications.status_changed'),
            'newStatusId' => $newStatusId
        ]);
    }

    /**
     * Assign a box to a deleivery man
     */
    public function assignBox($id, $userId) {

        $status = $this->repository->find($id)->update(['assigned_user_id' => $userId]);

        if(!$status)
            return response()->json([
                'success' => false,
                'message' => trans('notifications.error_occured'),
            ]);

        return response()->json([
            'success' => true,
            'message' => trans('notifications.status_changed'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data = [
            'list_services' => $this->repositories['ServiceRepository']->all(),
            'daira'      => Daira::all()->filter(function($daira){
                return $daira->wilaya->availability == 1;
            }),
            'validator' => jsValidator::make($this->getBoxRules())
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
        $newBox = $request->validate($this->getBoxRules());

        // Generate unique integer token  in boxes table
        $newBox['code'] = Token::UniqueNumber('boxes', 'code', 10);

        // Calculate Total price automatically
        $newBox['total_price'] = (int)$newBox['price'] + (int)$this->repositories['ConfigsRepository']->findWhere(['name' => 'delivery_price'])->content;

        $this->repository->create($newBox);

        return view($this->base_view . 'show', ['box' => $newBox])->with('success', trans('notifications.box_created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view($this->base_view . 'show', ['box' => $this->repository->find($id)]);
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
            'daira' => Daira::all()->filter(function($daira){
                return $daira->wilaya->availability == 1;
            }),
            'box'       => $this->repository->find($id),
            'validator' => jsValidator::make($this->getBoxRules())
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
        $oldBox = $this->repository->find($id);
        $newBox = $request->validate($this->getBoxRules());

        // Calculate Total price automatically
        if($oldBox->price != $request->price)
            $newBox['total_price'] = (int)$newBox['price'] + (int)$this->repositories['ConfigsRepository']->findWhere(['name' => 'delivery_price'])->content;

        $this->repository->update($newBox, $id);
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

    public function getBoxRules() {
        return [
            'full_name'  => 'required',
            'tel'        => 'required',
            'address'    => 'required',
            'price'      => 'required',
            'service_id' => 'required',
            'daira_id'   => 'required',
            'note'       => 'nullable|string',
            'daira_id'   => 'required',
            'service_id' => 'required',
        ];
    }
}
