<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Storage;
use App\Helpers\CommonHelper;
use DB;
use Session;
use Auth;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
class StudentController extends Controller
{
    private $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $students =  $this->studentRepository->allStudents($request->all());
            Log::info($students);
            return DataTables::of($students)
                ->addIndexColumn()

                ->addColumn('action',function ($row){
                    $data = '<td class="text-center hidden-print"><div class="dropdown">
                    <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action<span class="caret"></span></button>
                    <ul class="dropdown-menu">';
                        if($row->status == 1){
                            //$data .= '<li><a onclick="showDetailModelOneParamerter(\'fees/addStudentFeesForm\', ' . $row->id . ', \'Add Student Fees Detail\')"><span class="glyphicon glyphicon-eye-open"></span> Add Student Fees</a></li>';
                            $data .= '<li><a href="'.route('students.edit', $row->id).'"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>';
                            $data .= '<li><a id="inactive-record" data-url="'.route('students.destroy', $row->id).'">Inactive</a></li>';
                            //$data .= '<li><a onclick="showDetailModelOneParamerter(\'students/updateCurrentParaForm\','.$row->id.',\'Update Current Para Detail\')"><span class="glyphicon glyphicon-eye-open"></span> Update Current Para</a></li>';
                            //$data .= '<li><a href="'.route('updateStudentDocumentForm', $row->id).'"><span class="glyphicon glyphicon-eye-open"></span> Update Student Document</a></li>';
                        }else{
                            $data .= '<li><a id="active-record" data-url="'.route('students.active', $row->id).'">Active</a></li>';
                        }
                        if($row->suspended == 1){
                            $data .= '<li><a id="suspend-record" data-url="'.route('students.suspend', $row->id).'">Unsuspended</a></li>';
                        }else{
                            $data .= '<li><a id="unsuspended-record" data-url="'.route('students.unsuspended', $row->id).'">Suspend</a></li>';
                        }
                    $data .= '</ul></div></td>';
                    return $data;
                })
                ->rawColumns(['emp_type','action'])
                ->make(true);
        }
        return view('students.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles  = Role::all();
        return view('students.create',compact('roles'));
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
            'date_of_admission' => 'required',
            'student_name' => 'required',
            'date_of_birth' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'father_qualification' => 'required',
            'city_id' => 'required',
            'class_id' => 'required',
            'cnic_no' => 'required',
            'mobile_no' => 'required',
            'father_occupation' => 'required',
            'mother_tongue' => 'required',
            'home_address' => 'required',
            'birth_certificate' => '',
            'father_guardian_cnic' => '',
            'father_guardian_cnic_back' => '',
            'passport_size_photo' => '',
            'reference' => '',
            'mother_qualification' => '',
            'parent_email' => '',
            'specify_any_health_problem_medication' => '',
            'concession_fees' => ''
        ]);


        $registrationNo = Student::RegistrationNo();
        Storage::disk('public')->makeDirectory('StudentDocument/'.$registrationNo.'');
        $destinationPath = 'storage/app/public/StudentDocument/'.$registrationNo.'';

        $birth_certificate = $request->file('birth_certificate');
        $father_guardian_cnic = $request->file('father_guardian_cnic');
        $father_guardian_cnic_back = $request->file('father_guardian_cnic_back');
        $passport_size_photo = $request->file('passport_size_photo');
        
        if(empty($birth_certificate)){
            $data['birth_certificate'] = '-';
        }else{
            $birthCertificate = date('YmdHis') . "_1." . $birth_certificate->getClientOriginalExtension();
            $birth_certificate->move($destinationPath, $birthCertificate);
            $data['birth_certificate'] = $destinationPath.'/'.$birthCertificate;
        }

        if(empty($father_guardian_cnic)){
            $data['father_guardian_cnic'] = '-';
        }else{
            $fatherGuardianCNIC = date('YmdHis') . "_2." . $father_guardian_cnic->getClientOriginalExtension();
            $father_guardian_cnic->move($destinationPath, $fatherGuardianCNIC);
            $data['father_guardian_cnic'] = $destinationPath.'/'.$fatherGuardianCNIC;
        }

        if(empty($father_guardian_cnic_back)){
            $data['father_guardian_cnic_back'] = '-';
        }else{
            $fatherGuardianCNICBack = date('YmdHis') . "_3." . $father_guardian_cnic_back->getClientOriginalExtension();
            $father_guardian_cnic_back->move($destinationPath, $fatherGuardianCNICBack);
            $data['father_guardian_cnic_back'] = $destinationPath.'/'.$fatherGuardianCNICBack;
        }

        if(empty($passport_size_photo)){
            $data['passport_size_photo'] = '-';
        }else{
            $passportSizePhoto = date('YmdHis') . "_4." . $passport_size_photo->getClientOriginalExtension();
            $passport_size_photo->move($destinationPath, $passportSizePhoto);
            $data['passport_size_photo'] = $destinationPath.'/'.$passportSizePhoto;
        }

        $this->studentRepository->storeStudent($data);


        return redirect()->route('students.index')->with('message', 'Student Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        $student = $this->studentRepository->findStudent($request->get('id'));

        return view('students.studentDetail', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = $this->studentRepository->findStudent($id);

        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'date_of_admission' => 'required',
            'student_name' => 'required',
            'date_of_birth' => 'required',
            'grade_class_applied_for' => '',
            'father_name' => 'required',
            'mother_name' => 'required',
            'father_qualification' => 'required',
            'city_id' => 'required',
            'class_id' => 'required',
            'cnic_no' => 'required',
            'mobile_no' => 'required',
            'father_occupation' => 'required',
            'mother_tongue' => 'required',
            'home_address' => 'required',
            'concession_fees' => '',
            'previous_school' => '',
            'reference' => '',
            'mother_qualification' => '',
            'parent_email' => '',
            'specify_any_health_problem_medication' => ''
        ]);


        // $registrationNo = Student::RegistrationNo($request->input('student_section_type'));
        // Storage::disk('public')->makeDirectory('StudentDocument/'.$registrationNo.'');
        // $destinationPath = 'storage/app/public/StudentDocument/'.$registrationNo.'';

        // $birth_certificate = $request->file('birth_certificate');
        // $father_guardian_cnic = $request->file('father_guardian_cnic');
        // $passport_size_photo = $request->file('passport_size_photo');
        // $copy_of_last_report = $request->file('copy_of_last_report');
        // $consession_fees_image = $request->file('consession_fees_image');

        // $birthCertificate = date('YmdHis') . "_1." . $birth_certificate->getClientOriginalExtension();
        // $birth_certificate->move($destinationPath, $birthCertificate);
        // $data['birth_certificate'] = $destinationPath.'/'.$birthCertificate;

        // $fatherGuardianCNIC = date('YmdHis') . "_2." . $father_guardian_cnic->getClientOriginalExtension();
        // $father_guardian_cnic->move($destinationPath, $fatherGuardianCNIC);
        // $data['father_guardian_cnic'] = $destinationPath.'/'.$fatherGuardianCNIC;

        // $passportSizePhoto = date('YmdHis') . "_3." . $passport_size_photo->getClientOriginalExtension();
        // $passport_size_photo->move($destinationPath, $passportSizePhoto);
        // $data['passport_size_photo'] = $destinationPath.'/'.$passportSizePhoto;

        // if(empty($copy_of_last_report)){
        //     //$data['copy_of_last_report'] = '';
        // }else{
        //     $copyOfLastReport = date('YmdHis') . "_4." . $copy_of_last_report->getClientOriginalExtension();
        //     $copy_of_last_report->move($destinationPath, $copyOfLastReport);
        //     $data['copy_of_last_report'] = $destinationPath.'/'.$copyOfLastReport;
        // }

        // if(empty($consession_fees_image)){
        //     //$data['consession_fees_image'] = '';
        // }else{
        //     $consessionFeesImage = date('YmdHis') . "_5." . $consession_fees_image->getClientOriginalExtension();
        //     $consession_fees_image->move($destinationPath, $consessionFeesImage);
        //     $data['consession_fees_image'] = $destinationPath.'/'.$consessionFeesImage;
        // }

        $this->studentRepository->updateStudent($data, $id);

        return redirect()->route('students.index')->with('message', 'Student Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $this->studentRepository->changeStudentStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->studentRepository->changeStudentStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }

    public function changeSuspendToUnsuspendedRecord($id){
        $studentDetail = DB::table('students')->where('id',$id)->first();
        if($studentDetail->suspended == 1){
            $value = 2;
        }else{
            $value = 1;
        }

        DB::table('students')->where('id',$id)->update(['suspended' => $value]);
        DB::table('users')->where('student_id',$id)->update(['suspended' => $value]);
        return response()->json(['success' => 'Updated Successfully!']);
    }

    public function updateCurrentParaForm(Request $request){
        $id = $request->get('id');
        $currentParaDetail = $this->studentRepository->getCurrentParaDetail($id);
        $remainingParasList = CommonHelper::get_all_student_wise_remaining_paras($id);
        return view('students.updateCurrentParaForm', compact('currentParaDetail','remainingParasList'));
    }

    public function montlyPerformanceReport(Request $request){
        if($request->ajax()){
            return view('students.montlyPerformanceReportAjax');
        }
        return view('students.montlyPerformanceReport');
    }

    public function comletedParasList(Request $request){
        if($request->ajax()){
            $studentId = $request->input('filter_student_id');
            $completedParasList = DB::table('student_performance_para_wise_view')
                ->when($studentId != '', function ($q) use ($studentId) {
                    return $q->where('student_id','=',$studentId);
                })
                ->where('company_id',Session::get('company_id'))
                ->get();
            return view('students.comletedParasListAjax',compact('completedParasList'));
        }

        return view('students.comletedParasList');
    }


    public function viewParaPerformanceDetail(Request $request){
        $detail = $request->get('id');
        $explodeDetail = explode('<*>',$detail);
        $studentId = $explodeDetail[1];
        $paraId = $explodeDetail[0];

        $student = $this->studentRepository->findStudent($studentId);
        //$paraPerformanceDetail =
        return view('students.viewParaPerformanceDetail',compact('student'));
    }

    public function updateCurrentParaDetail(Request $request){
        $data = $request->validate([
            'para_id' => 'required',
            'student_id' => 'required'
        ]);

        $this->studentRepository->updateCurrentParaDetail($data,$request->input('student_id'),$request->input('privious_para_id'));

        return redirect()->route('students.index')->with('message', 'Student Current Para Detail Updated Successfully');
    }

    public function updateStudentDocumentForm($id){
        $student = $this->studentRepository->findStudent($id);
        return view('students.updateStudentDocumentForm',compact('student'));
    }


    public function updateStudentDocumentDetail(Request $request){
        $student = $this->studentRepository->findStudent($request->id);
        $fieldArray = $request->input('fieldArray');
        $registrationNo = $student->registration_no;
        Storage::disk('public')->makeDirectory('StudentDocument/'.$registrationNo.'');
        $destinationPath = 'storage/app/public/StudentDocument/'.$registrationNo.'';

        foreach($fieldArray as $faRow){
            $option = $request->input('option_'.$faRow.'');
            if($option == 1){
                $image = $request->file('image_'.$faRow.'');
                $column_name = $request->input('column_name_'.$faRow.'');

                $imageTwo = date('YmdHis') . "_".$faRow."." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $imageTwo);
                $data[$column_name] = $destinationPath.'/'.$imageTwo;
                if($faRow == 5){
                    DB::table('students')->where('id',$request->id)->update($data);
                    $data = array();
                }else{
                    DB::table('student_document_against_registrations')->where('student_id',$request->id)->update($data);
                    $data = array();
                }
            }
        }
        return redirect()->route('students.index')->with('message', 'Student Document Updated Successfully');
    }

    public function add_student_activity_performance(Request $request){
        $getAllStudents =  CommonHelper::get_all_students();
        $heads = DB::table('heads')->where('company_id',Session::get('company_id'))->get();
        if($request->ajax()){
            $studentId = $request->input('filter_student_id');
            $getStudentDetail = DB::table('students as s')
                ->join('student_current_paras as scp', 's.id', '=', 'scp.student_id')
                ->join('paras as p', 'scp.para_id', '=', 'p.id')
                ->select('s.id', 's.registration_no', 's.student_name', 'p.para_name', 'scp.para_id')
                ->when($studentId != '', function ($q) use ($studentId) {
                    return $q->where('s.id','=',$studentId);
                })
                ->where('s.company_id',Session::get('company_id'))
                ->where('s.company_location_id',Session::get('company_location_id'))
                ->where('scp.para_status', 1)
                ->get();
            $heads = DB::table('heads')->where('company_id',Session::get('company_id'))->get();
            $levelOfPerformances = DB::table('level_of_performances')->where('company_id',Session::get('company_id'))->get();
            return view('students.add_student_activity_performance_ajax', compact('getStudentDetail','heads','levelOfPerformances'));
        }


        return view('students.add_student_activity_performance', compact('getAllStudents','heads'));
    }

    public function add_student_activity_performance_store(Request $request){
        $studentIdArray = $request->input('student_id_array');
        $heads = DB::table('heads')->where('company_id',Session::get('company_id'))->get();
        $sabqiPerformanceDetail = [];
        foreach($studentIdArray as $sia){
            $sabqi_para_id = $request->input('sabqi_para_id_'.$sia.'');
            $sabqi_para_performance = $request->input('sabqi_para_performance_'.$sia.'');

            $sabqiPerformanceDetail[] = [
                'student_id' => $sia,
                'para_id' => $sabqi_para_id,
                'level_of_performance_id' => $sabqi_para_performance,
                'month_year' => date('Y-m-d'),
                'status' => 1,
                'created_by' => Auth::user()->name,
                'created_date' => date('Y-m-d')
            ];

            $aaData = array(
                'student_id' => $sia,
                'month_year' => date('Y-m-d'),
                'status' => 1,
                'created_by' => Auth::user()->name,
                'created_date' => date('Y-m-d')
            );
            $aaId = DB::table('additional_activities')->insertGetId($aaData);
            $aadData = [];
            foreach($heads as $hRow){
                $aadData[] = [
                    'additional_activity_id' => $aaId,
                    'head_id' => $hRow->id,
                    'level_of_performance_id' => $request->input(strtolower(str_replace(' ', '_', $hRow->head_name)).'_'.$sia.''),
                    'status' => 1,
                    'created_by' => Auth::user()->name,
                    'created_date' => date('Y-m-d')
                ];
            }
            DB::table('additional_activity_datas')->insert($aadData);

            $completedParasList = DB::table('student_current_paras')->where('student_id',$sia)->where('para_status',2)->get();
            if(count($completedParasList) != 0){
                $mpData = array(
                    'student_id' => $sia,
                    'month_year' => date('Y-m-d'),
                    'status' => 1,
                    'created_by' => Auth::user()->name,
                    'created_date' => date('Y-m-d')
                );
                $mpId = DB::table('manzil_performances')->insertGetId($mpData);
                $mpdData = [];
                foreach($completedParasList as $cplRow){
                    $mpdData[] = [
                        'manzil_performance_id' => $mpId,
                        'para_id' => $cplRow->para_id,
                        'level_of_performance_id' => $request->input('manzil_performance_'.$sia.''),
                        'status' => 1,
                        'created_by' => Auth::user()->name,
                        'created_date' => date('Y-m-d')
                    ];
                }
                DB::table('manzil_performance_datas')->insert($mpdData);
            }

        }
        DB::table('sabqi_performances')->insert($sabqiPerformanceDetail);
        return redirect()->route('add-student-activity-performance')->with('message', 'Student Performance Add Successfully');
    }

    public function attendance_list(Request $request)
    {
        if ($request->ajax()) {
            // Fetching filter parameters
            $studentId = $request->input('filter_student_id');
            $monthYear = $request->input('filter_month_year');

            // Handle month and year extraction from filter input
            list($year, $month) = $monthYear ? explode('-', $monthYear) : [null, null];

            // Build the query to retrieve attendance list
            $attendanceListQuery = DB::table('student_attendances as sa')
                ->join('students as s', 'sa.student_id', '=', 's.id')
                ->select(
                    's.id',
                    's.registration_no',
                    's.student_name',
                    DB::raw('COUNT(sa.id) as total_days'),
                    DB::raw('SUM(CASE WHEN sa.attendence_type = 1 THEN 1 ELSE 0 END) as present_days'),
                    DB::raw('SUM(CASE WHEN sa.attendence_type = 2 THEN 1 ELSE 0 END) as leave_days'),
                    DB::raw('SUM(CASE WHEN sa.attendence_type = 3 THEN 1 ELSE 0 END) as late_days'),
                    DB::raw('SUM(CASE WHEN sa.attendence_type = 4 THEN 1 ELSE 0 END) as absent_days')
                )
                ->where('sa.company_id', Session::get('company_id'))
                ->where('sa.company_location_id', Session::get('company_location_id'))
                ->when($year, function ($query) use ($year) {
                    return $query->whereYear('sa.attendence_date', $year);
                })
                ->when($month, function ($query) use ($month) {
                    return $query->whereMonth('sa.attendence_date', $month);
                })
                ->when($studentId, function ($query) use ($studentId) {
                    return $query->where('sa.student_id', $studentId);
                })
                ->groupBy('sa.student_id', 's.registration_no', 's.student_name');

            // Execute the query and retrieve the attendance list
            $attendanceList = $attendanceListQuery->get();

            // Return the result in the view for AJAX request
            return view('students.attendance_list_ajax', compact('attendanceList', 'monthYear'));
        }

        // If it's not an AJAX request, return the normal view
        return view('students.attendance_list');
    }

    public function viewStudentAttendanceDetail(Request $request){

        $paraDetail = $request->input('id');
        $explodeParamDetail = explode('<*>',$paraDetail);
        $studentId = $explodeParamDetail[0];
        $monthYear = $explodeParamDetail[1];
        $explodeMonthYear = explode('-',$monthYear);
        $year = $explodeMonthYear[0];
        $month = $explodeMonthYear[1];

        $studentInformation = DB::table('students as s')
            ->join('student_parent_and_guardian_informations as spagi', 's.id', '=', 'spagi.student_id')
            ->select(
                's.id',
                's.registration_no',
                's.student_name',
                'd.department_name',
                's.date_of_admission',
                'spagi.father_name',
                'spagi.mobile_no',
                'e.emp_name'
            )
            ->where('s.id',$studentId)
            ->first();
        $studentAttendanceDetail = DB::table('student_day_wise_performances as sdwp')
            ->whereMonth('sdwp.performance_date', $month)
            ->whereYear('sdwp.performance_date', $year)
            ->where('sdwp.student_id',$studentId)
            ->where('sdwp.company_id',Session::get('company_id'))
            ->where('sdwp.company_location_id',Session::get('company_location_id'))
            ->get();
        return view('students.viewStudentAttendanceDetail',compact('studentInformation','studentAttendanceDetail'));

    }
}
