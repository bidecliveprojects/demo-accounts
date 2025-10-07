<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\AcademicStatusRepositoryInterface;

class AcademicStatusController extends Controller
{
    private $academicStatusRepository;

    public function __construct(AcademicStatusRepositoryInterface $academicStatusRepository)
    {
        $this->academicStatusRepository = $academicStatusRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
        $academicstatus =  $this->academicStatusRepository->allAcademicStatus($request->all());

        return view('academicstatus.indexAjax', compact('academicstatus'));
        }
        return view('academicstatus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('academicstatus.create');
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
            'name' => 'required|string|max:255',
        ]);

        $this->academicStatusRepository->storeAcademicStatus($data);

        return redirect()->route('academic.status.index')->with('message', 'AcademicStatus Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $academicstatus = $this->academicStatusRepository->findAcademicStatus($id);

        return view('academicstatus.edit', compact('academicstatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->academicStatusRepository->updateAcademicStatus($data, $id);

        return redirect()->route('academic.status.index')->with('message', 'AcademicStatus Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $this->academicStatusRepository->changeAcademicStatusStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->academicStatusRepository->changeAcademicStatusStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }
}
