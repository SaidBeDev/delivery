<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Controllers\Backend\BackendBaseController;

use App\Http\SaidTech\Repositories\ContactsRepository\ContactRepository;
use App\Http\SaidTech\Repositories\ContactTypesRepository\ContactTypeRepository;

use jsValidator;

class ContactController extends BackendBaseController
{
    /**
     * @var ContactRepository
     */

    protected $repository;

    public function __construct(
        ContactRepository $repository,
        ContactTypeRepository $contactTypeRepository
    )
    {
        $this->repository = $repository;
        $this->repositories['ContactTypesRepository'] = $contactTypeRepository;

        $this->middleware('superAdmin');

        $this->setRubricConfig('contacts');
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
            'list_contacts' => $this->repository->all(),
            'contact_types' => $this->repositories['ContactTypesRepository']->all()
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
            'contact_types' => $this->repositories['ContactTypesRepository']->all(),
            'validator'     => jsValidator::make($this->getContactRules())
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

        $newContact = $request->validate($this->getContactRules());

        $this->repository->create($newContact);

        return redirect()->route('admin.contacts.index')->with(['success' => trans('notifications.contact_added')]);
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
            'contact'       => $this->repository->find($id),
            'contact_types' => $this->repositories['ContactTypesRepository']->all(),
            'validator'     => jsValidator::make($this->getContactRules())
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
        $newContact = $request->validate($this->getContactRules());

        $status = $this->repository->update($newContact, $id);

        if(!$status)
            return view($this->base_view . 'index')->with(['success' => false,'message' => trans('notifications.error_occured')]);

        return view($this->base_view . 'index')->with(['success' => true,'message' => trans('notifications.contact_updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return response()->json(['success' => true,'message' => trans('notifications.contact_deleted')]);
    }

    public function getContactRules() {
        return [
            'content' => 'required',
            'contact_type_id' => 'required',
        ];
    }
}
