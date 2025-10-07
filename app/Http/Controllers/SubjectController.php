<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\SubjectRepositoryInterface;

class SubjectController extends Controller
{
    private $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
        $subjects =  $this->subjectRepository->allSubjects($request->all());

        return view('subjects.indexAjax', compact('subjects'));
        }
        return view('subjects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subjects.create');
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
            'subject_name' => 'required|string|max:255',
            'section_id' => 'required|integer|exists:sections,id'
        ]);

        $this->subjectRepository->storeSubject($data);

        return redirect()->route('subjects.index')->with('message', 'Subject Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subject = $this->subjectRepository->findSubject($id);

        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'subject_name' => 'required|string|max:255',
            'section_id' => 'required|integer|exists:sections,id'
        ]);

        $this->subjectRepository->updateSubject($data, $id);

        return redirect()->route('subjects.index')->with('message', 'Subject Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $this->subjectRepository->changeSubjectStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->subjectRepository->changeSubjectStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }
}
