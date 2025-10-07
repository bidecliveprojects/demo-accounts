<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Models\SabqiPerformance;
use DB;
use Session;
use Auth;
class SabqiPerformanceController extends Controller
{
    private $page;
    public function __construct()
    {
        $this->page = 'sabqi-performance.';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $studentId = $request->input('filter_student_id');
            
            $sabqiPerformanceList = DB::table('sabqi_performances as sp')
                ->join('students as s', 'sp.student_id', '=', 's.id')
                ->join('paras as p', 'sp.para_id', '=', 'p.id')
                ->join('level_of_performances as lop', 'sp.level_of_performance_id', '=', 'lop.id')
                ->select('sp.month_year','s.student_name', 'p.para_name', 'lop.performance_name')
                ->when($studentId != '', function ($q) use ($studentId){
                    return $q->where('sp.student_id','=',$studentId);
                })
                ->get();
            return view($this->page.'indexAjax',compact('sabqiPerformanceList'));
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
        $data = $request->validate([
            'month_year' => 'required',
            'student_id' => 'required',
            'para_id' => 'required',
            'level_of_performance_id' => 'required'
        ]);
        $data['month_year'] .= '-01';
        $data['created_by'] = Auth::user()->name;
        $data['created_date'] = date('Y-m-d');
        
        DB::table('sabqi_performances')->insert($data);


        return redirect()->route('sabqi-performance.index')->with('message', 'Sabqi Performance Created Successfully');
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
