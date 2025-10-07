<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Models\ManzilPerformance;
use App\Models\ManzilPerformanceData;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Auth;

class ManzilPerformanceController extends Controller
{
    private $page;
    public function __construct()
    {
        $this->page = 'manzil-performance.';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $studentId = $request->input('filter_student_id');
            
            $manzilPerformanceList = DB::table('manzil_performances as mp')
                ->join('students as s', 'mp.student_id', '=', 's.id')
                ->select('mp.id','mp.month_year','s.student_name')
                ->when($studentId != '', function ($q) use ($studentId){
                    return $q->where('mp.student_id','=',$studentId);
                })
                ->get();
            return view($this->page.'indexAjax',compact('manzilPerformanceList'));
        }
        $getAllStudents = CommonHelper::get_all_students(1);
        return view($this->page.'index',compact('getAllStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getAllLevelOfPerformances = CommonHelper::get_all_level_of_performance(1);
        $getAllStudents = CommonHelper::get_all_students(1);
        $getAllParas = DB::table('paras as p')
            ->join('para_other_details as pod', 'p.id', '=', 'pod.para_id')
            ->select('p.*')
            ->where('pod.company_id', '=', Session::get('company_id'))
            ->where('pod.company_location_id', '=', Session::get('company_location_id'))
            ->get();
        return view($this->page.'create',compact('getAllLevelOfPerformances','getAllStudents','getAllParas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'error'=> $validator->errors()->toArray()
            ]);
        }else{
            $month_year = $request->input('month_year');
            $student_id = $request->input('student_id');
            $manzilPerformanceArray = $request->input('manzilPerformanceArray');

            $data['month_year'] = $month_year.'-01';
            $data['student_id'] = $student_id;
            $data['created_by'] = Auth::user()->name;
            $data['created_date'] = date('Y-m-d');

            $id = ManzilPerformance::insertGetId($data);
            foreach($manzilPerformanceArray as $mpaRow){
                $para_id = $request->input('para_id_'.$mpaRow.'');
                $level_of_performance_id = $request->input('level_of_performance_id_'.$mpaRow.'');
                $data2['manzil_performance_id'] = $id;
                $data2['para_id'] = $para_id;
                $data2['level_of_performance_id'] = $level_of_performance_id;
                $data2['created_by'] = Auth::user()->name;
                $data2['created_date'] = date('Y-m-d');
                
                ManzilPerformanceData::insert($data2);
            }
            return redirect()->route('manzil-performance.index')->with('message', 'Manzil Performance Created Successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
