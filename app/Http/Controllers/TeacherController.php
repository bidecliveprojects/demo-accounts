<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Storage;

class TeacherController extends Controller
{
    private $teacherRepository;

    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $teachers =  $this->teacherRepository->allTeachers($request->all());
            return view('teachers.indexAjax', compact('teachers'));
        }
        return view('teachers.index');


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teachers.create');
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
            'teacher_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'teacher_name' => 'required',
            'gender_id' => 'required',
            'section_id' => 'required',
            'teacher_mobile_no' => 'required',
            'teacher_address' => 'required',
            'teacher_cnic' => 'required',
            'department_id' => 'required'
        ]);

        if ($image = $request->file('teacher_image')) {
            $registrationNo = Teacher::RegistrationNo();
            Storage::disk('public')->makeDirectory('TeacherDocument/'.$registrationNo.'');
            $destinationPath = 'storage/app/public/TeacherDocument/'.$registrationNo.'';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $data['teacher_image'] = $destinationPath.'/'.$profileImage;
        }

        $this->teacherRepository->storeTeacher($data);

        return redirect()->route('teachers.index')->with('message', 'Teacher Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = $this->teacherRepository->findTeacher($id);

        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'teacher_name' => 'required',
            'gender_id' => 'required',
            'section_id' => 'required',
            'teacher_mobile_no' => 'required',
            'teacher_address' => 'required',
            'teacher_cnic' => 'required',
            'department_id' => 'required'
        ]);

        if($request->input('change_image_option') == 2){
            if ($image = $request->file('teacher_image')) {
                $registrationNo = Teacher::RegistrationNo();
                Storage::disk('public')->makeDirectory('TeacherDocument/'.$registrationNo.'');
                $destinationPath = 'storage/app/public/TeacherDocument/'.$registrationNo.'';
                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $profileImage);
                $data['teacher_image'] = $destinationPath.'/'.$profileImage;
            }
        }


        $this->teacherRepository->updateTeacher($data, $id);

        return redirect()->route('teachers.index')->with('message', 'Teacher Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $this->teacherRepository->changeTeacherStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->teacherRepository->changeTeacherStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }
}
