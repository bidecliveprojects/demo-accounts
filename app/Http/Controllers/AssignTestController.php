<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentAssignTestStatus;
use App\Models\StudentAttendence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\AssignTest;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class AssignTestController extends Controller
{
    protected $isApi;
    public function __construct(Request $request)
    {
        // Check if the request is for API
        $this->isApi = $request->is('api/*');
        // Define the base view path for web views
        $this->page = 'assign-test.';
    }

    /**
     * Store a new test in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function create()
     {
         // Return error response if accessed via API
         if ($this->isApi) {
             return response()->json([
                 'success' => false,
                 'message' => 'Invalid endpoint for API.',
             ], 400);
         }
 
         // Retrieve essential data
         $empId = Auth::user()->emp_id;
         $empIdsArray = Auth::user()->emp_ids_array;
         $schoolId = Session::get('company_id');
         $schoolCampusId = Session::get('company_location_id');
         $empTypeMultipleCampus = Auth::user()->emp_type_multiple_campus;
 
         // Build the query for subject teacher assignments
         $query = DB::table('subject_teacher_assignments as sta')
             ->select('sta.*','s.section_name','c.class_no','c.class_name')
             ->where('sta.company_id', $schoolId)
             ->where('sta.company_location_id', $schoolCampusId);
        if(Auth::user()->acc_type == 'superadmin'){
            // Filter based on employee type
            if ($empTypeMultipleCampus == 1) {
                $query->where('sta.teacher_id', $empId);
            } else {
                // Decode the JSON string into an array
                $empIdsArray = collect(json_decode($empIdsArray))
                    ->pluck('emp_id')
                    ->toArray();
                $query->whereIn('sta.teacher_id', $empIdsArray);
            }
        }
 
         // Fetch assigned subjects
         $subjectTeacherAssignments = $query->join('sections as s','sta.section_id','=','s.id')->join('classes as c','s.class_id','=','c.id')->get();
 
         // Render the view with the assigned subjects
         return view($this->page . 'create', compact('subjectTeacherAssignments'));
     }

     public function loadSubjectDependTeacherAndSectionIds(Request $request){
        $teacherId = $request->input('teacherId');
        $sectionId = $request->input('sectionId');
        $schoolId = $request->input('schoolId');
        $schoolCampusId = $request->input('schoolCampusId');

        $loadSubjectList = DB::table('subject_teacher_assignments as sta')
            ->select('sta.*','s.subject_name')
            ->join('subjects as s','sta.subject_id','=','s.id')
            ->where('sta.teacher_id',$teacherId)
            ->where('sta.section_id',$sectionId)
            ->where('sta.company_id',$schoolId)
            ->where('sta.company_location_id',$schoolCampusId)
            ->get();
        $data = '<option value="">Select Subject</option>';
        foreach($loadSubjectList as $lslRow){
            $data .= '<option value="'.$lslRow->id.'">'.$lslRow->subject_name.'</option>';
        }
        return $data;
    }

    /**
     * Common JSON Response Handler
     */
    private function jsonResponse($data, $totalRecords, $message = '', $status = 'success', $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'total_records' => $totalRecords
        ], $code);
    }

    /**
     * Handle Web Response
     */
    private function webResponse($view, $data = [])
    {
        return view($this->page . $view, $data);
    }

    /**
     * Validate Filters
     */
    private function validateFilters(Request $request)
    {
        return $request->validate([
            'type' => 'nullable|string|in:1,2', // Status filter
            'employee_id' => 'nullable|integer|exists:employees,id', // Employee filter
            'student_id' => 'nullable|integer|exists:students,id', // Student filter
            'from_date' => 'nullable|date', // Start date filter
            'to_date' => 'nullable|date|after_or_equal:from_date', // End date filter
            'section_id' => 'nullable|integer|exists:sections,id', // Section filter
            'subject_id' => 'nullable|integer|exists:subjects,id', // Subject filter
        ]);
    }

    /**
     * Build Assign Test Query
     */
    private function buildAssignTestQuery(Request $request, array $filters)
    {
        return AssignTest::with('studentAssignTestStatus')
            ->where('company_id', $request->input('company_id'))
            ->where('company_location_id', $request->input('company_location_id'))
            ->when($filters['type'] ?? null, fn($query) => $query->where('assign_test_status', $filters['type']))
            ->when($filters['employee_id'] ?? null, fn($query) => $query->where('employee_id', $filters['employee_id']))
            ->when($filters['student_id'] ?? null, fn($query) => $query->whereHas('studentAssignTestStatus', fn($subQuery) => $subQuery->where('student_id', $filters['student_id'])))
            ->when($filters['from_date'] ?? null, fn($query) => $query->where('start_date', '>=', $filters['from_date']))
            ->when($filters['to_date'] ?? null, fn($query) => $query->where('end_date', '<=', $filters['to_date']))
            ->when($filters['section_id'] ?? null, fn($query) => $query->where('section_id', $filters['section_id']))
            ->when($filters['subject_id'] ?? null, fn($query) => $query->where('subject_id', $filters['subject_id']));
    }

    /**
     * Index Method - Handles Assign Test retrieval
     */
    public function index(Request $request)
    {
        if($request->ajax() || $this->isApi){
            try {
                // Validate input filters
                $filters = $this->validateFilters($request);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
    
            // Build the query with filters
            $assignTestQuery = $this->buildAssignTestQuery($request, $filters);
    
            // If pagination is needed, you can adjust here (e.g., paginate(10) for 10 records per page)
            $assignTests = $assignTestQuery->get(); // or ->paginate(10) for paginated data
            $totalRecords = $assignTests->count();
    
            
    
            // If rendering in a web view (for non-API requests)
            if (!$this->isApi) {
                return $this->webResponse('indexAjax', compact('assignTests'));
            }
    
            // Return JSON response
            return $this->jsonResponse($assignTests, $totalRecords, 'Assign Tests Retrieved Successfully', 'success', 200);
        }
        if(!$this->isApi){
            $empId = Auth::user()->emp_id;
            $empIdsArray = Auth::user()->emp_ids_array;
            $schoolId = Session::get('company_id');
            $schoolCampusId = Session::get('company_location_id');
            $empTypeMultipleCampus = Auth::user()->emp_type_multiple_campus;

            // Build the query for subject teacher assignments
            $query = DB::table('subject_teacher_assignments as sta')
                ->select('sta.*','s.section_name','c.class_no','c.class_name')
                ->where('sta.company_id', $schoolId)
                ->where('sta.company_location_id', $schoolCampusId);
            if(Auth::user()->acc_type == 'superadmin'){
                // Filter based on employee type
                if ($empTypeMultipleCampus == 1) {
                    $query->where('sta.teacher_id', $empId);
                } else {
                    // Decode the JSON string into an array
                    $empIdsArray = collect(json_decode($empIdsArray))
                        ->pluck('emp_id')
                        ->toArray();
                    $query->whereIn('sta.teacher_id', $empIdsArray);
                }
            }

            // Fetch assigned subjects
            $subjectTeacherAssignments = $query->join('sections as s','sta.section_id','=','s.id')->join('classes as c','s.class_id','=','c.id')->get();
            return view($this->page.'index',compact('subjectTeacherAssignments'));
        }
    }

    public function studentTestSummaryAndPerformance(Request $request){
        echo 'Student Test Summary And Performance';
    }

    public function updateMultipleStudentTestStatus(Request $request)
    {
        // Validate input data
        $validatedData = Validator::make($request->all(), [
            'company_id' => 'nullable|integer|exists:companies,id',
            'company_location_id' => 'nullable|integer|exists:company_locations,id',
            'testArray' => 'required|array',
            'testArray.*.assign_test_id' => 'required|integer|exists:assign_tests,id',
            'testArray.*.student_id' => 'required|integer|exists:students,id',
            'testArray.*.assign_test_status' => 'required|integer|in:1,2',
            'testArray.*.no_of_marks_recieved' => 'required|integer',
        ]);



        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validatedData->errors(),
                'data' => null,
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Iterate through each test and update the database
            foreach ($request->testArray as $test) {
                $assigntest = DB::table('assign_tests')->where('id', $test['assign_test_id'])->first();
                if ($test['no_of_marks_recieved'] > $assigntest->no_of_marks) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Of Marks Given is greater then Total Number',
                    ]);

                }


                DB::table('student_assign_test_status')
                    ->where('assign_test_id', $test['assign_test_id'])
                    ->where('student_id', $test['student_id'])
                    ->update([
                        'assign_test_status' => $test['assign_test_status'],
                        'no_of_marks_recieved' => $test['no_of_marks_recieved'],
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Test assignment statuses updated successfully!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating test statuses.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //  public function index(Request $request)
    // {
    //     try {
    //         // Validate input data for filters
    //         $data = $request->validate([
    //             'type' => 'nullable|string|in:1,2', // Status filter
    //             'employee_id' => 'nullable|integer|exists:employees,id', // Employee filter
    //             'student_id' => 'nullable|integer|exists:students,id', // Student filter
    //             'from_date' => 'nullable|date', // Start date filter
    //             'to_date' => 'nullable|date|after_or_equal:from_date', // End date filter
    //             'section_id' => 'nullable|integer|exists:sections,id', // Section filter
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     }

    //     // Build query with filters
    //     $assignTestQuery = AssignTest::with('studentAssignTestStatus')
    //         ->where('company_id', $request->input('company_id'))
    //         ->where('company_location_id', $request->input('company_location_id'))
    //         ->when(!empty($data['type']), function ($query) use ($data) {
    //             $query->where('assign_test_status', $data['type']);
    //         })
    //         ->when(!empty($data['employee_id']), function ($query) use ($data) {
    //             $query->where('employee_id', $data['employee_id']);
    //         })
    //         ->when(!empty($data['student_id']), function ($query) use ($data) {
    //             $query->whereHas('studentAssignTestStatus', function ($subQuery) use ($data) {
    //                 $subQuery->where('student_id', $data['student_id']);
    //             });
    //         })
    //         ->when(!empty($data['from_date']), function ($query) use ($data) {
    //             $query->where('start_date', '>=', $data['from_date']);
    //         })
    //         ->when(!empty($data['to_date']), function ($query) use ($data) {
    //             $query->where('end_date', '<=', $data['to_date']);
    //         })
    //         ->when(!empty($data['section_id']), function ($query) use ($data) {
    //             $query->where('section_id', $data['section_id']);
    //         })
    //         ->when(!empty($data['subject_id']), function ($query) use ($data) {
    //             $query->where('subject_id', $data['subject_id']);
    //         });


    //     // Get the records and their count
    //     $assignTests = $assignTestQuery->get();
    //     $totalRecords = $assignTestQuery->count();

    //     // Return response
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Assign Tests Retrieved Successfully',
    //         'data' => $assignTests,
    //         'total_records' => $totalRecords,
    //     ], 200);
    // }

    public function show($id)
    {
        $assignTestDetail = AssignTest::with('subject')->where('id', $id)->first();
        if (is_null($assignTestDetail)) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Assign Test Not Found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Assign Test Detail Retrieved successfully',
            'data' => $assignTestDetail,
        ], 200);
    }

    public function updateAssignTestStatus(Request $request)
    {
        // Validate input data
        $data = $request->validate([
            'assign_test_id' => 'required|integer|exists:assign_tests,id',
            'student_id' => 'required|integer|exists:students,id',
            'assign_test_status' => 'required|string',
            'submission_date' => 'required|date',
        ]);

        // Check if the record exists
        $studentAssignTestStatusDetail = DB::table('student_assign_test_status')
            ->where('assign_test_id', $data['assign_test_id'])
            ->where('student_id', $data['student_id'])
            ->first();

        if (!$studentAssignTestStatusDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Assign test status not found for the given student.',
            ], 404);
        }

        // Update the record
        try {
            DB::table('student_assign_test_status')
                ->where('assign_test_id', $data['assign_test_id'])
                ->where('student_id', $data['student_id'])
                ->update([
                    'assign_test_status' => $data['assign_test_status'],
                    'submission_date' => $data['submission_date'],
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Test assignment status updated successfully!',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating test status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update test assignment status. Please try again later.',
            ], 500);
        }
    }
    public function changeStatus(Request $request)
    {
        $data = $request->validate([
            'assign_test_id' => 'required|integer|exists:assign_tests,id',
            'assign_test_status' => 'required'
        ]);

        $assingTestDetail = AssignTest::where('id', $request->input('assign_test_id'))->first();
        $assingTestDetail->assign_test_status = $request->input('assign_test_status');
        $assingTestDetail->update();

        return response()->json([
            'success' => true,
            'message' => 'Test assigned changed successfully!',
        ], 201);
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'employee_id' => 'required|integer',
                'section_id' => 'required|integer',
                'subject_id' => 'required|integer',
                'type' => 'nullable|string|max:255',
                'student_ids_array' => 'nullable|array',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'no_of_marks' => 'nullable|integer|min:0',
                'company_id' => 'required|integer',
                'company_location_id' => 'required|integer',
                'assign_test_status' => 'required|integer'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Prepare data for insertion into 'assign_tests' table
        $dataToInsert = [
            'employee_id' => $validatedData['employee_id'],
            'section_id' => $validatedData['section_id'],
            'subject_id' => $validatedData['subject_id'],
            'type' => $validatedData['type'] ?? 1,
            'student_ids_array' => json_encode($validatedData['student_ids_array'] ?? []),
            'title' => $validatedData['title'],
            'description' => $validatedData['description'] ?? null,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'no_of_marks' => $validatedData['no_of_marks'] ?? 0,
            'created_date' => now()->format('Y-m-d'),
            'status' => 1,
            'created_by' => Auth::user()->name ?? 'System',
            'company_id' => $validatedData['company_id'],
            'company_location_id' => $validatedData['company_location_id'],
            'assign_test_status' => $validatedData['assign_test_status']
        ];

        try {
            // Insert test into the 'assign_tests' table and get the test ID
            $assignTestId = DB::table('assign_tests')->insertGetId($dataToInsert);

            // Determine student list based on test type
            $studentArray = ($validatedData['type'] == 1)
                ? DB::table('students')
                    ->where('section_id', $validatedData['section_id'])
                    ->where('company_id', $validatedData['company_id'])
                    ->where('company_location_id', $validatedData['company_location_id'])
                    ->get()
                : collect($validatedData['student_ids_array']);

            // Prepare test details for notification
            $testDetails = [
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? 'No description available',
            ];

            // Prepare data for student assignments
            $studentAssignments = [];
            $notificationData = []; // To store notification data to insert into database
            $studentIds = [];

            foreach ($studentArray as $student) {
                $studentId = is_object($student) ? $student->id : $student;

                // Prepare student assignments
                $studentAssignments[] = [
                    'assign_test_id' => $assignTestId,
                    'student_id' => $studentId,
                    'assign_test_status' => 1,
                    'status' => 1,
                    'created_by' => Auth::user()->name ?? '-',
                    'created_date' => now()->format('Y-m-d'),
                ];

                // Prepare notification data
                $notificationData[] = [
                    'student_id' => $studentId,
                    'type' => '1',
                    'data' => json_encode($testDetails),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Store student ID for bulk notification dispatch
                $studentIds[] = $studentId;
            }

            // Use a database transaction to ensure all operations are atomic
            DB::transaction(function () use ($studentAssignments, $notificationData, $studentIds) {
                // Batch insert student assignments
                DB::table('student_assign_test_status')->insert($studentAssignments);

                // Batch insert notifications into the notifications table
                DB::table('notifications')->insert($notificationData);

            });
            if (!$this->isApi) {
                return redirect()->route('assign-tests.index')->with('success', 'Test assigned successfully and notifications sent!');
            }
            return response()->json([
                'success' => true,
                'message' => 'Test assigned successfully and notifications sent!',
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('Error assigning test: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign test. Please try again later.',
            ], 500);
        }
    }


    public function update(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'assign_test_id' => 'required|integer|exists:assign_tests,id', // Test ID validation
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'no_of_marks' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        try {
            // Find the test by ID
            $test = AssignTest::findOrFail($request->assign_test_id);

            // Update the test with validated data
            $test->title = $request->title;
            $test->description = $request->description;
            $test->start_date = $request->start_date;
            $test->end_date = $request->end_date;
            $test->no_of_marks = $request->no_of_marks;

            // Save the test
            $test->save();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Test updated successfully.',
                'data' => $test,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the test.',
                'data' => null,
            ], 500);
        }
    }


    public function studentList(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'test_id' => 'required|integer|exists:assign_tests,id', // Test ID validation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        // Fetch the due_date from the assign_tests table
        $assignTest = AssignTest::find($request->test_id);
        if (!$assignTest) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Assign test not found',
                'data' => null,
            ], 404);
        }
        $dueDate = $assignTest->end_date;

        // Fetch the student assign test data
        $assignTestQuery = StudentAssignTestStatus::with('student')
            ->where('assign_test_id', $request->test_id)
            ->get();

        // Calculate total count
        $statusCounts = [
            'Total Count' => $assignTestQuery->count(),
            'Absent Count' => $assignTestQuery->where('no_of_marks_recieved', 0)->count(),
            'Submitted Count' => $assignTestQuery->filter(function ($item) {
                return $item->assign_test_status == 2 && $item->no_of_marks_recieved > 0;
            })->count(),
        ];

        // Prepare response data with attendance check
        $responseData = $assignTestQuery->map(function ($detail) use ($dueDate) {
            // Check attendance for the student on the due_date
            $attendance = StudentAttendence::where('student_id', $detail->student_id)
                ->where('attendence_date', $dueDate)
                ->first();

            Log::info($attendance);
            Log::info($dueDate);
            Log::info($detail->student_id);

            $isAbsent = !$attendance || $attendance->attendence_type != 2 ? false : true;

            return [
                'id' => $detail->id,
                'student_id' => $detail->student_id,
                'student_name' => $detail->student->student_name,
                'registration_no' => $detail->student->registration_no,
                'no_of_marks_recieved' => $detail->no_of_marks_recieved,
                'assign_test_status' => $detail->assign_test_status,
                'is_absent' => $isAbsent, // New field indicating attendance
            ];
        });

        // Return response with total count and status-wise count
        return response()->json([
            'success' => true,
            'message' => 'Student List Retrieved Successfully!',
            'status_counts' => $statusCounts, // Custom counts with labels
            'data' => $responseData,
        ], 200);
    }

}
