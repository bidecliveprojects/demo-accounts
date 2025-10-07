<?php

namespace App\Http\Controllers;

use App\Models\ClassTeacherAssignments;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\StudentAttendence;
use Illuminate\Support\Facades\Validator;

class StudentAttendenceController extends Controller
{
    protected $isApi;
    public function __construct(Request $request)
    {
        // Check if the request is for API
        $this->isApi = $request->is('api/*');
        // Define the base view path
        $this->page = 'student-attendance.';
    }

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
        $query = DB::table('class_teacher_assignments as cta')
            ->select('cta.*', 's.section_name','c.class_no','c.class_name')
            ->where('cta.company_id', $schoolId)
            ->where('cta.company_location_id', $schoolCampusId);
        if(Auth::user()->acc_type == 'superadmin'){
            // Filter based on employee type
            if ($empTypeMultipleCampus == 1) {
                $query->where('cta.teacher_id', $empId);
            } else {
                // Decode the JSON string into an array
                $empIdsArray = collect(json_decode($empIdsArray))
                    ->pluck('emp_id')
                    ->toArray();
                $query->whereIn('cta.teacher_id', $empIdsArray);
            }
        }

        // Fetch assigned subjects
        $classTeacherAssignments = $query->join('sections as s', 'cta.section_id', '=', 's.id')->join('classes as c','s.class_id','=','c.id')->get();


        // Render the view with the assigned subjects
        return view($this->page . 'create', compact('classTeacherAssignments'));
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

    public function loadStudentDependTeacherAndSectionIds(Request $request)
    {
        $teacherId = $request->input('teacherId');
        $sectionId = $request->input('sectionId');
        $schoolId = $request->input('schoolId');
        $schoolCampusId = $request->input('schoolCampusId');
        $attendanceDate = $request->input('attendanceDate');

        // Build the query with the required conditions
        $query = DB::table('students as s')
            ->leftJoin('student_attendances as sa', function ($join) use ($attendanceDate) {
                $join->on('s.id', '=', 'sa.student_id');
                if ($attendanceDate) {
                    $join->where('sa.attendence_date', $attendanceDate);
                }
            })
            ->where('s.section_id', $sectionId)
            ->where('s.company_id', $schoolId)
            ->where('s.company_location_id', $schoolCampusId)
            ->select(
                's.id as student_id',
                's.registration_no as student_registration_no',
                's.student_name as student_name',
                'sa.id as attendance_id',
                'sa.attendence_date',
                'sa.attendence_type as attendance_status' // Include attendance status if needed
            );

        // Execute the query and get the student list
        $studentList = $query->get();

        // Return the view with the student list
        return view($this->page . 'createAjax', compact('studentList'));
    }

    public function storeMassAttendanceTwo(Request $request)
    {
        try {
            // Extract data from the request
            $sectionId = $request->input('section_id');
            $employeeId = $request->input('employee_id');
            $schoolId = $request->input('company_id');
            $schoolCampusId = $request->input('company_location_id');
            $attendanceDate = $request->input('attendance_date');
            $studentIdsArray = $request->input('studentIdsArray');

            // Begin transaction to ensure all records are inserted or none
            DB::beginTransaction();

            // Loop through each student and store attendance
            foreach ($studentIdsArray as $studentId) {
                // Get the attendance type for the student from the request
                $attendanceType = $request->input("attendence_type_" . $studentId);

                // Insert or update attendance for each student
                DB::table('student_attendances')->insert([
                    'company_id' => $schoolId,
                    'company_location_id' => $schoolCampusId,
                    'employee_id' => $employeeId,
                    'section_id' => $sectionId,
                    'student_id' => $studentId,
                    'attendence_date' => $attendanceDate,
                    'attendence_type' => $attendanceType,
                    'created_by' => Auth::user()->name,
                    'created_date' => date('Y-m-d'),
                    'status' => 1
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return redirect()->route('student-attendance.index')->with('message', 'Student Attendance Updated Successfully!');

        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while recording the attendance.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new task in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $this->isApi) {
            try {
                $data = $request->validate([
                    'attendence_type' => 'nullable|string|in:1,2,3,4', // Example statuses
                    'employee_id' => 'nullable|integer|exists:employees,id', // Optional employee ID, ensuring it exists in the 'employees' table
                    'student_id' => 'nullable|integer|exists:students,id', // Optional student ID, ensuring it exists in the 'students' table
                    'section_id' => 'nullable|integer|exists:sections,id', // Optional section ID, ensuring it exists in the 'sections' table
                    'from_date' => 'nullable|date', // Optional from_date, ensuring it's a valid date
                    'to_date' => 'nullable|date|after_or_equal:from_date', // Optional to_date, ensuring it's after or equal to from_date
                    'attendence_date' => 'nullable|date', // Optional to_date, ensuring it's after or equal to from_date
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            // Base query for retrieving attendance records
            $query = StudentAttendence::with('student:id,student_name,registration_no')
                ->where('company_id', $request->input('company_id'))
                ->where('company_location_id', $request->input('company_location_id'));

            // Apply filters to the query
            if (!empty($data['employee_id'])) {
                $query->where('employee_id', $data['employee_id']);
            }

            if (!empty($data['student_id'])) {
                $query->where('student_id', $data['student_id']);
            }

            if (!empty($data['from_date'])) {
                $query->where('attendence_date', '>=', $data['from_date']);
            }

            if (!empty($data['to_date'])) {
                $query->where('attendence_date', '<=', $data['to_date']);
            }

            if (!empty($data['attendence_date'])) {
                $query->where('attendence_date', '=', $data['attendence_date']);
            }

            if (!empty($data['attendence_type'])) {
                $query->where('attendence_type', $data['attendence_type']);
            }

            if (!empty($data['section_id'])) {
                $query->where('section_id', $data['section_id']);
            }



            // Retrieve filtered attendance records
            $studentAttendences = $query->get();

            // Compute attendance counts without filters for full overview
            $attendanceCounts = [
                'present' => StudentAttendence::where('company_id', $request->input('company_id'))
                    ->where('company_location_id', $request->input('company_location_id'))
                    ->when(!empty($data['section_id']), fn($q) => $q->where('section_id', $data['section_id']))
                    ->where('attendence_type', '1')->count(),
                'absent' => StudentAttendence::where('company_id', $request->input('company_id'))
                    ->where('company_location_id', $request->input('company_location_id'))
                    ->when(!empty($data['section_id']), fn($q) => $q->where('section_id', $data['section_id']))
                    ->where('attendence_type', '2')->count(),
                'late' => StudentAttendence::where('company_id', $request->input('company_id'))
                    ->where('company_location_id', $request->input('company_location_id'))
                    ->when(!empty($data['section_id']), fn($q) => $q->where('section_id', $data['section_id']))
                    ->where('attendence_type', '3')->count(),
                'leave' => StudentAttendence::where('company_id', $request->input('company_id'))
                    ->where('company_location_id', $request->input('company_location_id'))
                    ->when(!empty($data['section_id']), fn($q) => $q->where('section_id', $data['section_id']))
                    ->where('attendence_type', '4')->count(),
            ];

            // Prepare the attendance data
            $responseData = $studentAttendences->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'attendence_date' => $detail->attendence_date,
                    'attendence_type' => $detail->attendence_type,
                    'student_id' => $detail->student->id,
                    'student_name' => $detail->student->student_name,
                    'registration_no' => $detail->student->registration_no,
                ];
            });

            if (!$this->isApi) {
                return $this->webResponse('indexAjax', compact('responseData'));
            }

            // Return the response
            return response()->json([
                'status' => 'success',
                'message' => 'Student Attendances Retrieved Successfully',
                'data' => $responseData,
                'counts' => $attendanceCounts, // Include the full attendance counts
            ], 200);
        }
        if (!$this->isApi) {
            // Retrieve essential data
            $empId = Auth::user()->emp_id;
            $empIdsArray = Auth::user()->emp_ids_array;
            $schoolId = Session::get('company_id');
            $schoolCampusId = Session::get('company_location_id');
            $empTypeMultipleCampus = Auth::user()->emp_type_multiple_campus;

            // Build the query for subject teacher assignments
            $query = DB::table('class_teacher_assignments as cta')
                ->select('cta.*', 's.section_name','c.class_no','c.class_name')
                ->where('cta.company_id', $schoolId)
                ->where('cta.company_location_id', $schoolCampusId);

            if(Auth::user()->acc_type == 'superadmin'){
                // Filter based on employee type
                if ($empTypeMultipleCampus == 1) {
                    $query->where('cta.teacher_id', $empId);
                } else {
                    // Decode the JSON string into an array
                    $empIdsArray = collect(json_decode($empIdsArray))
                        ->pluck('emp_id')
                        ->toArray();
                    $query->whereIn('cta.teacher_id', $empIdsArray);
                }
            }

            // Fetch assigned subjects
            $classTeacherAssignments = $query->join('sections as s', 'cta.section_id', '=', 's.id')->join('classes as c','s.class_id','=','c.id')->get();
            return view($this->page . 'index', compact('classTeacherAssignments'));
        }
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'attendence_date' => 'required|date',
            'attendence_type' => 'required',
            'employee_id' => 'required|exists:employees,id',
            'section_id' => 'required|exists:sections,id',
            'student_id' => 'required|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        // Add additional fields to the data
        $validatedData['created_date'] = now()->format('Y-m-d');
        $validatedData['status'] = 1;
        $validatedData['created_by'] = Auth::user()->name ?? 'System'; // Fallback for unauthenticated users
        $validatedData['company_id'] = $request->input('company_id');
        $validatedData['company_location_id'] = $request->input('company_location_id');

        try {
            $validatedData['student_id'] = $request->student_id;
            $validatedData['section_id'] = $request->section_id;
            $validatedData['employee_id'] = $request->employee_id;
            $validatedData['attendence_date'] = $request->attendence_date;
            $validatedData['attendence_type'] = $request->attendence_type;

            // Insert the attendance record into the database
            DB::table('student_attendances')->insert($validatedData);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Student attendance added successfully!',
            ], 201);
        } catch (\Exception $e) {
            // Log the error and return an error response
            \Log::error('Error Student Attendance: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add student attendance. Please try again later.',
            ], 500);
        }
    }


    public function storeMassAttendance(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'attendence_date' => 'required|date',
            'employee_id' => 'required',
            'section_id' => 'required',
            'students' => 'required|array', // Students array required
            'students.*.student_id' => 'required', // Each student ID must exist
            'students.*.attendence_type' => 'required|in:1,2,3,4' // Validate attendance types
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        // Add additional fields
        $createdDate = now()->format('Y-m-d');
        $createdBy = Auth::user()->name ?? 'System';
        $schoolId = $request->input('company_id');
        $campusId = $request->input('company_location_id');

        try {


            $failedEntries = [];
            $successCount = 0;

            // Loop through the list of students to mark attendance
            foreach ($request->students as $studentData) {
                $studentId = $studentData['student_id'];
                $attendenceType = $studentData['attendence_type'];


                // Prepare attendance data for insertion
                $attendanceData = [
                    'attendence_date' => $request->attendence_date,
                    'attendence_type' => $attendenceType, // Use dynamic attendence_type
                    'employee_id' => $request->employee_id,
                    'section_id' => $request->section_id,
                    'student_id' => $studentId,
                    'created_date' => $createdDate,
                    'status' => 1,
                    'created_by' => $createdBy,
                    'company_id' => $schoolId,
                    'company_location_id' => $campusId,
                ];

                // Insert attendance data
                DB::table('student_attendances')->insert($attendanceData);
                $successCount++;
            }

            // Build the response message
            return response()->json([
                'status' => 'success',
                'message' => "Mass attendance marked successfully.",
                'summary' => [
                    'total_students' => count($request->students),
                    'attendance_marked' => $successCount,
                    'failed_entries' => $failedEntries,
                ],
            ], 201);

        } catch (\Exception $e) {
            // Log the error and return a failure response
            \Log::error('Error in Mass Student Attendance: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to mark attendance. Please try again later.',
                'data' => null
            ], 500);
        }
    }

}
