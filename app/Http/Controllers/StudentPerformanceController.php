<?php

namespace App\Http\Controllers;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\StudentPerformanceRepositoryInterface;
use App\Models\Student;
use App\Models\ParaOtherDetail;
use App\Models\StudentDayWisePerformance;
use App\Models\StudentCurrentPara;
use App\Models\LevelOfPerformance;
use Session;
use DB;
use App\Imports\StudentPerformanceImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;
use DateTime;

class StudentPerformanceController extends Controller
{
    private $studentPerformanceRepository;

    public function __construct(StudentPerformanceRepositoryInterface $studentPerformanceRepository)
    {
        $this->studentPerformanceRepository = $studentPerformanceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $getAllParas = CommonHelper::get_all_paras();
        $getAllStudents = CommonHelper::get_all_students();
        if($request->ajax()){
            $allStudentPerformances = $this->studentPerformanceRepository->allStudentPerformances($request->all());
            return view('studentperformances.indexAjax',compact('allStudentPerformances'));
        }
        return view('studentperformances.index',compact('getAllParas','getAllStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $getAllParas = CommonHelper::get_all_paras(1);
        $getAllStudents = CommonHelper::get_all_students(1);
        $getAllTeachers = CommonHelper::get_all_teachers(1);
        if($request->ajax()){
            $updatedParaStudentList =  $this->studentPerformanceRepository->getUpdatedParaStudentList($request->all());
            $data['fromDate'] = $request->get('fromDate');
            $data['toDate'] = $request->get('toDate');
            return view('studentperformances.createAjax',compact('updatedParaStudentList','data'));
        }
        return view('studentperformances.create',compact('getAllStudents','getAllParas','getAllTeachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->studentPerformanceRepository->storeStudentPerformance($data);
        return redirect()->route('studentperformances.index')->with('message', 'Student Performance Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $paramDetail = $request->get('id');
        $explodeParamDetail = explode('<*>',$paramDetail);
        $studentId = $explodeParamDetail[0];
        $paraId = $explodeParamDetail[1];
        $studentDetail = Student::select(
            'students.id',
            'students.registration_no',
            'students.date_of_admission',
            'students.date_of_birth',
            'students.student_name',
            'students.concession_fees',
            'student_parent_and_guardian_informations.father_name',
            'student_parent_and_guardian_informations.mobile_no',
            'student_parent_and_guardian_informations.parent_email',
            'student_parent_and_guardian_informations.father_occupation',
            'student_parent_and_guardian_informations.home_address',
            'departments.department_name',
            'departments.department_fees',
            'employees.emp_name',
            'classes.class_name',
            'emp.emp_name as nazim_name'
            
        )
        ->join('student_parent_and_guardian_informations', 'students.id', '=', 'student_parent_and_guardian_informations.student_id')
        ->leftJoin('classes', 'students.teacher_id', '=', 'classes.teacher_id')
        ->leftJoin('companies', 'students.company_id', '=', 'companies.id')
        ->leftJoin('employees as emp', 'companies.nazim_id', '=', 'emp.id')
        ->where('students.id',$studentId)
        ->first();
        $paraDetails = ParaOtherDetail::select(
            'paras.id',
            'paras.para_name',
            'para_other_details.total_lines_in_para',
            'para_other_details.estimated_completion_days',
            'para_other_details.excelent',
            'para_other_details.good',
            'para_other_details.average'
        )
        ->join('paras', 'para_other_details.para_id', '=', 'paras.id')
        ->where('para_other_details.para_id', $paraId)
        ->where('para_other_details.company_id', Session::get('company_id'))
        ->where('para_other_details.company_location_id', Session::get('company_location_id'))
        ->first();
        $studentPerformances = StudentDayWisePerformance::where('para_id', $paraId)
            ->where('student_id', $studentId)
            ->where('company_id',Session::get('company_id'))
            ->get();
        return view('studentperformances.viewStudentPerformanceParaWiseDetail', compact('studentDetail','paraId'));
    }

    public function loadPerformanceDetailAgainstType(Request $request){
        $performanceType = $request->get('performanceType');
        if($performanceType == 3){
            return $this->studentPerformanceAgainstCompleteQuran($request->all());
        }else if($performanceType == 2){
            return $this->studentMonthlyPerformanceAgainstPara($request->all());
        }else {
            return $this->studentDailyPerformanceAgainstPara($request->all());
        }
    }

    public function studentDailyPerformanceAgainstPara($request){
        $paraId = $request['paraId'];
        $studentId = $request['studentId'];
        $paraDetails = ParaOtherDetail::select(
            'paras.id',
            'paras.para_name',
            'para_other_details.total_lines_in_para',
            'para_other_details.estimated_completion_days',
            'para_other_details.excelent',
            'para_other_details.good',
            'para_other_details.average'
        )
        ->join('paras', 'para_other_details.para_id', '=', 'paras.id')
        ->where('para_other_details.para_id', $paraId)
        ->where('para_other_details.company_id', Session::get('company_id'))
        ->where('para_other_details.company_location_id', Session::get('company_location_id'))
        ->first();
        $studentPerformances = StudentDayWisePerformance::where('para_id', $paraId)
            ->where('student_id', $studentId)
            ->where('company_id',Session::get('company_id'))
            ->get();
        $studentPerformancesTwo = StudentDayWisePerformance::where('para_id', $paraId)
            ->where('student_id', $studentId)
            ->where('company_id',Session::get('company_id'))
            ->where('performance_activity_type', '=', 1)
            ->get();
        
        return view('studentperformances.studentDailyPerformanceAgainstPara', compact('paraDetails','studentPerformances','studentPerformancesTwo'));
    }

    public function studentMonthlyPerformanceAgainstPara($request){
        return view('studentperformances.studentMonthlyPerformanceAgainstPara');
    }   

    public function studentPerformanceAgainstCompleteQuran($request){
        $studentId = $request['studentId'];

        $getParaDetailList = DB::table('para_other_details as pod')
            ->select('pod.*', 'p.para_no', 'p.para_name')
            ->join('paras as p', 'pod.para_id', '=', 'p.id')
            ->leftJoin('student_current_paras as scp', function ($join) use ($studentId) {
                $join->on('scp.para_id', '=', 'pod.para_id')
                    ->where(function ($query) {
                        $query->where('scp.para_status', '=', 1);
                    })
                    ->where('scp.student_id', '=', $studentId);
            })
            ->where('pod.company_id', Session::get('company_id'))
            ->where('pod.company_location_id', Session::get('company_location_id'))
            ->orderBy('p.id', 'asc')
            ->get();
        
        
        
        $getCompletedParaList = DB::table('student_current_paras as scp')
            ->select('p.id', 'p.para_no', 'p.para_name', 'pod.total_lines_in_para', 'pod.excelent', 'pod.good', 'pod.average', 'pod.estimated_completion_days',
                DB::raw('(SELECT COUNT(sdwp.id) FROM student_day_wise_performances as sdwp 
                        WHERE sdwp.student_id = scp.student_id 
                        AND sdwp.para_id = scp.para_id 
                        AND sdwp.performance_activity_type = 1) AS total_lines_per_student'),
                DB::raw('(SELECT MIN(sdwp.performance_date) FROM student_day_wise_performances as sdwp 
                        WHERE sdwp.student_id = scp.student_id 
                        AND sdwp.para_id = scp.para_id 
                        AND sdwp.performance_activity_type = 1) AS start_date'),
                DB::raw('(SELECT MAX(sdwp.performance_date) FROM student_day_wise_performances as sdwp 
                        WHERE sdwp.student_id = scp.student_id 
                        AND sdwp.para_id = scp.para_id 
                        AND sdwp.performance_activity_type = 1) AS end_date')
            )
            ->join('paras as p', 'scp.para_id', '=', 'p.id')
            ->join('para_other_details as pod', 'scp.para_id', '=', 'pod.para_id')
            ->where('scp.student_id', $studentId)
            ->where('scp.para_status', 2)
            ->where('pod.company_id', Session::get('company_id'))
            ->where('pod.company_location_id', Session::get('company_location_id'))
            ->orderBy('scp.id', 'asc')
            ->get();

        return view('studentperformances.studentPerformanceAgainstCompleteQuran',compact('getParaDetailList','getCompletedParaList'));

    }

    public function viewMonthlyPerformanceReport(Request $request){
        if($request->ajax()){
            $company_id = Session::get('company_id');
            $studentId = $request->input('filter_student_id');
            $monthYear = $request->input('filter_month_year');
            $explodeMonthYear = explode("-",$monthYear);
            $month = $explodeMonthYear[1];
            $year = $explodeMonthYear[0];
            $startDate = date('Y-m-01', strtotime($monthYear));
            $endDate = date('Y-m-t', strtotime($monthYear));

            $date = new DateTime($monthYear);
            $date->modify('first day of previous month');
            $previousMonth = $date->format('Y-m');
            $pStartDate = $previousMonth.'-01';
            $pEndDate = date($previousMonth.'-t');
            $studentDetail = Student::select(
                'students.id',
                'students.registration_no',
                'students.date_of_admission',
                'students.date_of_birth',
                'students.student_name',
                'students.concession_fees',
                'student_parent_and_guardian_informations.father_name',
                'student_parent_and_guardian_informations.mobile_no',
                'student_parent_and_guardian_informations.parent_email',
                'student_parent_and_guardian_informations.father_occupation',
                'student_parent_and_guardian_informations.home_address',
                'departments.department_name',
                'departments.department_fees',
                'employees.emp_name',
                'classes.class_name',
                'emp.emp_name as nazim_name'
                
            )
            ->join('student_parent_and_guardian_informations', 'students.id', '=', 'student_parent_and_guardian_informations.student_id')
            ->leftJoin('classes', 'students.teacher_id', '=', 'classes.teacher_id')
            ->leftJoin('companies', 'students.company_id', '=', 'companies.id')
            ->leftJoin('employees as emp', 'companies.nazim_id', '=', 'emp.id')
            ->where('students.id',$studentId)
            ->first();
            $getSabqiPerformanceDetail = DB::table('sabqi_performances as sp')
                ->join('paras as p', 'sp.para_id', '=', 'p.id')
                ->join('level_of_performances as lop', 'sp.level_of_performance_id', '=', 'lop.id')
                ->whereRaw('sp.student_id = ? AND MONTH(sp.month_year) = ? AND YEAR(sp.month_year) = ?', [$studentId, $month, $year])
                ->select('p.para_no','p.para_name', 'lop.performance_name')
                ->get();
            $getManzilPerformanceDetail = DB::table('manzil_performance_datas as mpd')
                ->join('manzil_performances as mp', 'mpd.manzil_performance_id', '=', 'mp.id')
                ->join('paras as p', 'mpd.para_id', '=', 'p.id')
                ->join('level_of_performances as lop', 'mpd.level_of_performance_id', '=', 'lop.id')
                ->whereRaw('mp.student_id = ? AND MONTH(mp.month_year) = ? AND YEAR(mp.month_year) = ?', [$studentId, $month, $year])
                ->select('p.para_no', 'p.para_name', 'lop.performance_name')
                ->get();
            $getAdditionalActivityDetail = DB::table('additional_activity_datas as aad')
                ->join('additional_activities as aa', 'aad.additional_activity_id', '=', 'aa.id')
                ->join('heads as h', 'aad.head_id', '=', 'h.id')
                ->join('level_of_performances as lop', 'aad.level_of_performance_id', '=', 'lop.id')
                ->whereRaw('aa.student_id = ? AND MONTH(aa.month_year) = ? AND YEAR(aa.month_year) = ?', [$studentId, $month, $year])
                ->select('h.head_name', 'lop.performance_name','lop.marks')
                ->get();
            $sabaqSummaryInLines = DB::table('student_current_paras as scp')
                ->select('p.para_name','p.id', 'pod.total_lines_in_para', DB::raw('SUM(sdwp.no_of_lines) AS total_lines'))
                ->join('paras as p', 'scp.para_id', '=', 'p.id')
                ->join('para_other_details as pod', function ($join) {
                    $join->on('pod.para_id', '=', 'scp.para_id')
                        ->where('pod.company_id', '=', DB::raw('scp.company_id'));
                })
                ->leftJoin('student_day_wise_performances as sdwp', function ($join) {
                    $join->on('sdwp.para_id', '=', 'scp.para_id')
                        ->on('sdwp.student_id', '=', 'scp.student_id');
                })
                ->where('scp.para_status', 1)
                ->where('scp.student_id', $studentId)
                ->groupBy('p.para_name', 'pod.total_lines_in_para','p.id')
                ->first();
            $currentParaPriviousLines = DB::table('student_day_wise_performances')
                ->where('performance_date', '>=', $pStartDate)
                ->where('performance_date', '<=', $pEndDate)
                ->where('student_id', $studentId)
                ->where('para_id', $sabaqSummaryInLines->id)
                ->sum('no_of_lines');
            $levelOfPerformances = LevelOfPerformance::where('company_id',$company_id)->get();
            return view('studentperformances.viewMonthlyPerformanceReportAjax',compact('studentDetail','month','year','getSabqiPerformanceDetail','getManzilPerformanceDetail','getAdditionalActivityDetail','sabaqSummaryInLines','monthYear','currentParaPriviousLines','levelOfPerformances'));
        }
        return view('studentperformances.viewMonthlyPerformanceReport');
    }

    public function importStudentPerformance (){
        return view('studentperformances.importStudentPerformance ');
    }

    public function addImportStudentPerformanceDetail(Request $request){
        //print_r($request->all());
        if($request->hasFile('sample_file')){
            $path = $request->file('sample_file')->getRealPath();
            $data = Excel::import(new StudentPerformanceImport(), $path, null, ExcelType::XLSX);
        }
        return redirect()->route('studentperformances.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $studentPerformanceDetail = DB::table('student_day_wise_performances')->where('id',$id)->first();
        return view('studentperformances.edit',compact('studentPerformanceDetail'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $noOfLines = $request->input('no_of_lines');
        
        if($noOfLines == 'Holiday'){
            $data['performance_activity_type'] = 3;
            $data['no_of_lines'] = 0;
        }else if($noOfLines == 'Leave'){
            $data['performance_activity_type'] = 2;
            $data['no_of_lines'] = 0;
        }else{
            $totalLines += $noOfLines;
            $data['performance_activity_type'] = 1;
            $data['no_of_lines'] = $noOfLines;
        }
        
        DB::table('student_day_wise_performances')->where('id',$id)->update($data);
        return redirect()->route('studentperformances.index')->with('message', 'Student Performance Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function viewStudentPerformanceReport (Request $request){
        $getAllParas = CommonHelper::get_all_paras();
        $getAllStudents = CommonHelper::get_all_students();
        if($request->ajax()){
            $allStudentPerformancesReport = $this->studentPerformanceRepository->allStudentPerformancesReport($request->all());
            return view('studentperformances.studentPerformanceReportAjax',compact('allStudentPerformancesReport'));
        }
        return view('studentperformances.studentPerformanceReport',compact('getAllParas','getAllStudents'));
    }
}
