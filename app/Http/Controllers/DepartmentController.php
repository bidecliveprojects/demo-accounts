<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Repositories\Interfaces\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{
    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $departments =  $this->departmentRepository->allDepartments($request->all());

            return view('departments.indexAjax', compact('departments'));
        }
        return view('departments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create');
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
            'department_name' => 'required|string|max:255',
        ]);

        $this->departmentRepository->storeDepartment($data);

        return redirect()->route('departments.index')->with('message', 'Department Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    //     //echo $id;
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = $this->departmentRepository->findDepartment($id);
        if (!$department) {
            abort(404);
        }

        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        $this->departmentRepository->updateDepartment($data, $id);

        return redirect()->route('departments.index')->with('message', 'Department Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function status($id){
        $this->departmentRepository->changeDepartmentStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->departmentRepository->changeDepartmentStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }

    /**
     * Permanently delete a department only when no employees/teachers/etc. reference it.
     */
    public function destroy($id)
    {
        $department = $this->departmentRepository->findDepartment($id);

        if (!$department) {
            return response()->json([
                'catchError' => 'Department not found or access denied.',
            ]);
        }

        if ($this->departmentRepository->countDepartmentUsage((int) $id) > 0) {
            return response()->json([
                'catchError' => 'This department cannot be deleted because it is in use (e.g. employees or teachers).',
            ]);
        }

        $department->delete();

        return response()->json(['success' => 'Department deleted successfully.']);
    }
}
