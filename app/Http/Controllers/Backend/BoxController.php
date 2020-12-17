<?php

namespace App\Http\Controllers\Backend;

use App\Daira;
use App\Wilaya;

use jsValidator;
use Dirape\Token\Token;
/* use Barryvdh\DomPDF\PDF; */

use Barryvdh\DomPDF\PDF;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
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
        $this->repositories['ConfigsRepository'] = $configRepository;
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
            $user = $this->repositories['UserRepository']->find(Auth::id());

            switch($user->profile_type->name) {
                case 'superAdmin':
                    $data = [
                        'list_boxes'      => $this->repository->all(),
                        'box_status' => $this->repositories['BoxStatusRepository']->all(),
                        'list_delivrers' => $this->repositories['UserRepository']->whereHas('profile_type', function(Builder $query){
                            $query->where('name', '=', 'deliveryMan');
                        })->all()
                    ];
                break;

                case 'deliveryMan':
                    $data = [
                        'list_boxes'      => $this->repository->findWhere(['assigned_user_id' => Auth::id()]),
                        'box_status' => $this->repositories['BoxStatusRepository']->all(),
                    ];
                break;

                case 'distributor':
                    $data = [
                        'list_boxes'      => $this->repository->findWhere(['user_id' => Auth::id()]),
                        'box_status' => $this->repositories['BoxStatusRepository']->all(),
                    ];
                break;
            }
        }

        return view($this->base_view . 'index', ['data' => $data]);
    }

    /**
     * Get list of livred boxes
     * @void
     */
    public function getLivredBoxes() {
        $listBoxes = $this->repository->whereHas('box_status', function(Builder $query) {
            $query->where(['name', '=', 'Livrer']);
        })->all();

        $data = [
            'list_boxes' => $listBoxes
        ];

        return view('backend.rubrics.budgets.index', ['data' => $data]);
    }

    /**
     * Get list of livred boxes for a specefic user
     * @void
     */
    public function getLivredBoxesById($userId) {
        $listBoxes = $this->repository
                            ->findWhere(['user_id' => $userId])
                            ->whereHas('box_status', function(Builder $query) {
                                $query->where(['name', '=', 'Livrer']);
                            })->all();

        $data = [
            'list_boxes' => $listBoxes
        ];

        return view('backend.rubrics.budgets.show', ['data' => $data]);
    }

    /**
     * Make a box as recieved
     */
    public function setRecieved($code) {

        // Find the authenticated user
        $user = $this->repositories['UserRepository']->find(Auth::id());

        $box = $this->repository->findWhere(['code' => $code])->first();

        // Check that is either superadmin or delivery man
        if(in_array($user->profile_type->name, ['superAdmin'])) {
            $box->is_recieved = 1;
            $status = $box->save();

            if(!$status){
                return response()->json([
                    'success' => false,
                    'message' => trans('notifications.error_occured'),
                ]);
            }else {
                return response()->json([
                    'success' => true,
                    'message' => trans('notifications.received'),
                ]);
            }



        }
    }

    /**
     * Make a box as returned
     */
    public function setReturned($code) {

        // Find the authenticated user
        $user = $this->repositories['UserRepository']->find(Auth::id());

        // Check that is either superadmin or delivery man
        if(in_array($user->profile_type->name, ['superAdmin', 'deliveryMan'])) {

            $newBoxStatus = $this->repositories['BoxStatusRepository']->findWhere(['name' => 'Retour'])->first();
            $box = $this->repository->findWhere(['code' => $code])->first();

            $box->is_returned = 1;
            $box->box_status_id = $newBoxStatus->id;

            $status = $box->save();

            if(!$status)
                return response()->json([
                    'success' => false,
                    'message' => trans('notifications.error_occured'),
                ]);

            return response()->json([
                'success' => true,
                'message' => trans('notifications.returned'),
            ]);
        }
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
            'message' => trans('notifications.box_assigned'),
        ]);
    }

    /**
     * Show
     */
    public function showFile($id) {
        $box = $this->repository->find($id);

        return view($this->base_view . 'box_details', ['box' => $box]);
    }
    /**
     * Download as pdf
     */
    public function downlaodFile($id) {
        $box = $this->repository->find($id);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('backend.rubrics.boxes.box_details', ['box' => $box]);

        return $pdf->setPaper('a4', 'portrait')->save('backend/box.pdf')->stream('box.pdf');
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
            'list_dairas'   => Daira::all()->filter(function($daira){
                return $daira->wilaya->availability == 1;
            }),
            'list_wilayas'  => $this->repositories['WilayaRepository']->findWhere(['availability' => 1])->all(),
            'validator' => jsValidator::make($this->getBoxRules())
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
        $newBox = $request->validate($this->getBoxRules());

        // Generate unique integer token  in boxes table
        $token = new Token();
        $newBox['code'] = $token->UniqueNumber('boxes', 'code', 10);

        // Calculate Total price automatically
        $daira  = $this->repositories['DairaRepository']->find($request->daira_id);
        $wilaya = $this->repositories['WilayaRepository']->find($daira->wilaya->id);

        $newBox['total_price'] = (int)$newBox['price'] + (int)$wilaya->price;

        $newBox['user_id'] = Auth::id();

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
        $data = [
            'box' => $this->repository->find($id)
        ];

        return view($this->base_view . 'show', ['data' => $data]);
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
            'list_services' => $this->repositories['ServiceRepository']->all(),
            'list_wilayas'  => $this->repositories['WilayaRepository']->all(),
            'list_dairas' => Daira::all()->filter(function ($daira) {
                return $daira->wilaya->availability == 1;
            }),
            'box'       => $this->repository->find($id),
            'validator' => jsValidator::make($this->getBoxRules())
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
        $oldBox = $this->repository->find($id);
        $newBox = $request->validate($this->getBoxRules());

        // Calculate Total price automatically
        $daira  = $this->repositories['DairaRepository']->find($request->daira_id);
        $wilaya = $this->repositories['WilayaRepository']->find($daira->wilaya->id);

        if($oldBox->price != $request->price){
            $newBox['total_price'] = (int)$newBox['price'] + (int)$wilaya->price;
        }

        $this->repository->update($newBox, $id);

        return redirect()->route('admin.boxes.index')->with('success', trans('notifications.box_updated'));
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
