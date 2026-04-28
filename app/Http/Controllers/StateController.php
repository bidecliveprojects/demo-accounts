<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\StateRepositoryInterface;
use App\Services\MasterDataDeletionGuard;
use Illuminate\Support\Facades\Session;

class StateController extends Controller
{
    private $stateRepository;

    public function __construct(StateRepositoryInterface $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
        $states =  $this->stateRepository->allStates($request->all());

        return view('states.indexAjax', compact('states'));
        }
        return view('states.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('states.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'state_name' => 'required|string|max:255',
            'country_id' => 'required'
        ]);

        $this->stateRepository->storeState($data);
        generate_json('states');
        return redirect()->route('states.index')->with('message', 'State Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state = $this->stateRepository->findState($id);

        return view('states.edit', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'state_name' => 'required|string|max:255',
            'country_id' => 'required'
        ]);

        $this->stateRepository->updateState($data, $id);

        return redirect()->route('states.index')->with('message', 'State Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function status($id){
        $this->stateRepository->changeStateStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->stateRepository->changeStateStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }

    /**
     * Permanent delete (only when no cities reference this state for this company).
     */
    public function destroy($id)
    {
        $companyId = (int) Session::get('company_id');
        $check = MasterDataDeletionGuard::assertStateDeletable((int) $id, $companyId);
        if (! $check['ok']) {
            return response()->json([
                'catchError' => $check['message'],
            ]);
        }

        $this->stateRepository->deleteState($id, $companyId);
        generate_json('states');

        return response()->json(['success' => 'State deleted permanently.']);
    }
}
