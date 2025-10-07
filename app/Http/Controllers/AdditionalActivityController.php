<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Models\AdditionalActivity;
use App\Models\AdditionalActivityData;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Auth;

class AdditionalActivityController extends Controller
{
    private $page;
    public function __construct()
    {
        $this->page = 'additional-activity.';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $studentId = $request->input('filter_student_id');
            
            $additionalActivityList = DB::table('additional_activities as aa')
                ->join('students as s', 'aa.student_id', '=', 's.id')
                ->select('aa.id','aa.month_year','s.student_name')
                ->when($studentId != '', function ($q) use ($studentId){
                    return $q->where('aa.student_id','=',$studentId);
                })
                ->get();
            return view($this->page.'indexAjax',compact('additionalActivityList'));
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
        $getAllHeads = CommonHelper::get_all_heads(1);
        return view($this->page.'create',compact('getAllLevelOfPerformances','getAllStudents','getAllHeads'));
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
            $additionalActivityArray = $request->input('additionalActivityArray');

            $data['month_year'] = $month_year.'-01';
            $data['student_id'] = $student_id;
            $data['created_by'] = Auth::user()->name;
            $data['created_date'] = date('Y-m-d');

            $id = AdditionalActivity::insertGetId($data);
            foreach($additionalActivityArray as $aaaRow){
                $head_id = $request->input('head_id_'.$aaaRow.'');
                $level_of_performance_id = $request->input('level_of_performance_id_'.$aaaRow.'');
                $data2['additional_activity_id'] = $id;
                $data2['head_id'] = $head_id;
                $data2['level_of_performance_id'] = $level_of_performance_id;
                $data2['created_by'] = Auth::user()->name;
                $data2['created_date'] = date('Y-m-d');
                
                AdditionalActivityData::insert($data2);
            }
            return redirect()->route('additional-activity.index')->with('message', 'Additional Activity Created Successfully');
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
