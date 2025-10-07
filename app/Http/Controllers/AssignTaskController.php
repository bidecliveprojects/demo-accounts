<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\StudentAssignTaskStatus;
use App\Models\SubjectTeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\AssignTask;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CommonHelper;

class AssignTaskController extends Controller
{
    protected $isApi;
    public function __construct(Request $request)
    {
        // Check if the request is for API
        $this->isApi = $request->is('api/*');
        // Define the base view path for web views
        $this->page = 'assign-task.';
    }

    /**
     * Store a new task in the database.
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
        $schoolId = Session::get('company_id');
        $schoolCampusId = Session::get('company_location_id');

        // Build the query for subject teacher assignments
        $query = DB::table('subject_teacher_assignments as sta')
            ->where('sta.company_id', $schoolId)
            ->where('sta.company_location_id', $schoolCampusId)
            ->where('sta.teacher_id', $empId)
            ->get();

        log::info("Data Logged to Give to ChatGPT: " . $query);

        // Extract unique section_ids
        $section_ids = $query->pluck('section_id')->unique()->toArray();

        log::info("section_ids " . json_encode($section_ids));

        // Fetch all sections based on unique section_ids
        $sections = Section::with('classes')
            ->whereIn('id', $section_ids)
            ->where('company_id', $schoolId)
            ->where('company_location_id', $schoolCampusId)
            ->get();
        ;
        // Render the view with the sections
        return view($this->page . 'create', compact('sections', 'empId'));
    }

    public function edit($id)
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
        $schoolId = Session::get('company_id');
        $schoolCampusId = Session::get('company_location_id');

        // Build the query for subject teacher assignments
        $query = DB::table('subject_teacher_assignments as sta')
            ->where('sta.company_id', $schoolId)
            ->where('sta.company_location_id', $schoolCampusId)
            ->where('sta.teacher_id', $empId)
            ->get();


        // Extract unique section_ids
        $section_ids = $query->pluck('section_id')->unique()->toArray();


        // Fetch all sections based on unique section_ids
        $sections = Section::with('classes')
            ->whereIn('id', $section_ids)
            ->where('company_id', $schoolId)
            ->where('company_location_id', $schoolCampusId)
            ->get();

        $assignTask = AssignTask::find($id);
        return view($this->page . 'edit', compact('sections', 'empId', 'assignTask'));
    }

    public function studentWiseTaskSummaryAndPerformance(Request $request)
    {
        if ($request->ajax()) {

        }
        $query = DB::table('students as s')
            ->select('s.id', 's.student_name', 's.registration_no', 'sec.section_name', 'c.class_no', 'c.class_name', 's.section_id', 'sec.class_id', 's.company_id', 's.company_location_id')
            ->join('sections as sec', 's.section_id', '=', 'sec.id')
            ->join('classes as c', 'sec.class_id', '=', 'c.id');

        $studentList = $query->get();

        return view($this->page . 'student-wise-task-summary-and-performance', compact('studentList'));
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
     * Build Assign Task Query
     */
    private function buildAssignTaskQuery(Request $request, array $filters)
    {
        return AssignTask::with([
            'studentAssignTaskStatus',
            'subject:id,subject_name',
            'section:id,section_name,class_id', // Include class_id in the section relation
            'section.classes:id,class_no'
        ])
            ->where('company_id', $request->input('company_id'))
            ->where('company_location_id', $request->input('company_location_id'))
            ->when($filters['type'] ?? null, fn($query) => $query->where('assign_task_status', $filters['type']))
            ->when($filters['employee_id'] ?? null, fn($query) => $query->where('employee_id', $filters['employee_id']))
            ->when($filters['student_id'] ?? null, fn($query) => $query->whereHas('studentAssignTaskStatus', fn($subQuery) => $subQuery->where('student_id', $filters['student_id'])))
            ->when($filters['from_date'] ?? null, fn($query) => $query->where('start_date', '>=', $filters['from_date']))
            ->when($filters['to_date'] ?? null, fn($query) => $query->where('end_date', '<=', $filters['to_date']))
            ->when($filters['section_id'] ?? null, fn($query) => $query->where('section_id', $filters['section_id']))
            ->when($filters['subject_id'] ?? null, fn($query) => $query->where('subject_id', $filters['subject_id']));
    }

    /**
     * Index Method - Handles Assign Task retrieval
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $this->isApi) {
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
            $assignTaskQuery = $this->buildAssignTaskQuery($request, $filters);

            // If pagination is needed, you can adjust here (e.g., paginate(10) for 10 records per page)
            $assignTasks = $assignTaskQuery->get(); // or ->paginate(10) for paginated data
            $totalRecords = $assignTasks->count();



            // If rendering in a web view (for non-API requests)
            if (!$this->isApi) {
                return $this->webResponse('indexAjax', compact('assignTasks'));
            }

            // Return JSON response
            return $this->jsonResponse($assignTasks, $totalRecords, 'Assign Tasks Retrieved Successfully', 'success', 200);
        }
        if (!$this->isApi) {
            $empId = Auth::user()->emp_id;
            $schoolId = Session::get('company_id');
            $schoolCampusId = Session::get('company_location_id');


            $query = DB::table('subject_teacher_assignments as sta')
                ->where('sta.company_id', $schoolId)
                ->where('sta.company_location_id', $schoolCampusId)
                ->where('sta.teacher_id', $empId)
                ->get();


            // Extract unique section_ids
            $section_ids = $query->pluck('section_id')->unique()->toArray();


            // Fetch all sections based on unique section_ids
            $sections = Section::with('classes')
                ->whereIn('id', $section_ids)
                ->where('company_id', $schoolId)
                ->where('company_location_id', $schoolCampusId)
                ->get();


            return view($this->page . 'index', compact('sections' ,'empId'));
        }


    }

    public function loadSubjectDependTeacherAndSectionIds(Request $request)
    {
        $teacherId = $request->input('teacherId');
        $sectionId = $request->input('sectionId');
        $schoolId = $request->input('schoolId');
        $schoolCampusId = $request->input('schoolCampusId');
        $type = $request->input('type');
        $assigntask_subject_id = $request->input('assigntask_subject_id');

        $loadSubjectList = DB::table('subject_teacher_assignments as sta')
            ->select('sta.*', 's.subject_name')
            ->join('subjects as s', 'sta.subject_id', '=', 's.id')
            ->where('sta.teacher_id', $teacherId)
            ->where('sta.section_id', $sectionId)
            ->where('sta.company_id', $schoolId)
            ->where('sta.company_location_id', $schoolCampusId)
            ->get();
        $data = '<option value="">Select Subject</option>';
        if ($type == 1) {
            foreach ($loadSubjectList as $lslRow) {
                $data .= '<option value="' . $lslRow->subject_id . '">' . $lslRow->subject_name . '</option>';
            }
        } elseif ($type == 2) {

            foreach ($loadSubjectList as $lslRow) {
                $data .= '<option value="' . $lslRow->id . '" ' .
                    ($lslRow->subject_id == $assigntask_subject_id ? 'selected' : '') .
                    '>' . $lslRow->subject_name . '</option>';

            }
        }
        return $data;


    }

    public function updateMultipleStudentTaskStatus(Request $request)
    {
        // Validate input data
        $validatedData = $request->validate([
            'company_id' => 'nullable|integer|exists:companies,id',
            'company_location_id' => 'nullable|integer|exists:company_locations,id',
            'taskArray' => 'required|array',
            'taskArray.*.assign_task_id' => 'required|integer|exists:assign_tasks,id',
            'taskArray.*.student_id' => 'required|integer|exists:students,id',
            'taskArray.*.assign_task_status' => 'required|integer|in:1,2',
            'taskArray.*.submission_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // Iterate through each task and update the database
            foreach ($validatedData['taskArray'] as $task) {
                DB::table('student_assign_task_status')
                    ->where('assign_task_id', $task['assign_task_id'])
                    ->where('student_id', $task['student_id'])
                    ->update([
                        'assign_task_status' => $task['assign_task_status'],
                        'submission_date' => $task['submission_date'],
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task assignment statuses updated successfully!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating task statuses.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $assignTaskDetail = AssignTask::with('subject')->where('id', $id)->first();
        if (is_null($assignTaskDetail)) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Assign Task Not Found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Assign Task Detail Retrieved successfully',
            'data' => $assignTaskDetail,
        ], 200);
    }


    public function updateAssignTaskStatus(Request $request)
    {
        // Validate input data
        $data = $request->validate([
            'assign_task_id' => 'required|integer|exists:assign_tasks,id',
            'student_id' => 'required|integer|exists:students,id',
            'assign_task_status' => 'required|string',
            'submission_date' => 'required|date',
        ]);

        // Check if the record exists
        $studentAssignTaskStatusDetail = DB::table('student_assign_task_status')
            ->where('assign_task_id', $data['assign_task_id'])
            ->where('student_id', $data['student_id'])
            ->first();

        if (!$studentAssignTaskStatusDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Assign task status not found for the given student.',
            ], 404);
        }

        // Update the record
        try {
            DB::table('student_assign_task_status')
                ->where('assign_task_id', $data['assign_task_id'])
                ->where('student_id', $data['student_id'])
                ->update([
                    'assign_task_status' => $data['assign_task_status'],
                    'submission_date' => $data['submission_date'],
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Task assignment status updated successfully!',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating task status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task assignment status. Please try again later.',
            ], 500);
        }
    }
    public function changeStatus(Request $request)
    {
        $data = $request->validate([
            'assign_task_id' => 'required|integer|exists:assign_tasks,id',
            'assign_task_status' => 'required'
        ]);

        $assingTaskDetail = AssignTask::where('id', $request->input('assign_task_id'))->first();
        $assingTaskDetail->assign_task_status = $request->input('assign_task_status');
        $assingTaskDetail->update();

        return response()->json([
            'success' => true,
            'message' => 'Task assigned changed successfully!',
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
                'assign_task_status' => 'required|integer'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Prepare data for insertion into 'assign_tasks' table
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
            'assign_task_status' => $validatedData['assign_task_status']
        ];

        try {
            // Insert task into the 'assign_tasks' table and get the task ID
            $assignTaskId = DB::table('assign_tasks')->insertGetId($dataToInsert);

            // Determine student list based on task type
            $studentArray = ($validatedData['type'] == 1)
                ? DB::table('students')
                    ->where('section_id', $validatedData['section_id'])
                    ->where('company_id', $validatedData['company_id'])
                    ->where('company_location_id', $validatedData['company_location_id'])
                    ->get()
                : collect($validatedData['student_ids_array']);

            // Prepare task details for notification
            $taskDetails = [
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
                    'assign_task_id' => $assignTaskId,
                    'student_id' => $studentId,
                    'assign_task_status' => 1,
                    'status' => 1,
                    'created_by' => Auth::user()->name ?? '-',
                    'created_date' => now()->format('Y-m-d'),
                ];

                // Prepare notification data
                $notificationData[] = [
                    'student_id' => $studentId,
                    'type' => '1',
                    'data' => json_encode($taskDetails),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Store student ID for bulk notification dispatch
                $studentIds[] = $studentId;
            }

            // Use a database transaction to ensure all operations are atomic
            DB::transaction(function () use ($studentAssignments, $notificationData, $studentIds) {
                // Batch insert student assignments
                DB::table('student_assign_task_status')->insert($studentAssignments);

                // Batch insert notifications into the notifications table
                DB::table('notifications')->insert($notificationData);

            });
            if (!$this->isApi) {
                return redirect()->route('assign-tasks.index')->with('success', 'Task assigned successfully and notifications sent!');
            }
            return response()->json([
                'success' => true,
                'message' => 'Task assigned successfully and notifications sent!',
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error assigning task: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign task. Please try again later.',
            ], 500);
        }
    }



    public function update(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'assign_task_id' => 'required|integer|exists:assign_tasks,id', // Task ID validation
            'title' => 'required|string|max:255',
            'description' => 'required|string',
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
            // Find the task by ID
            $task = AssignTask::findOrFail($request->assign_task_id);

            // Update the task with validated data
            $task->title = $request->title;
            $task->description = $request->description;
            $task->start_date = $request->start_date;
            $task->end_date = $request->end_date;

            // Save the task
            $task->save();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully.',
                'data' => $task,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the task.',
                'data' => null,
            ], 500);
        }
    }



    public function destroy($id)
    {
        $AssignTask = AssignTask::where('id', $id)->update(['status' => 2]);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id)
    {
        $AssignTask = AssignTask::where('id', $id)->update(['status' => 1]);
        return response()->json(['success' => 'Active Successfully!']);
    }

    public function studentList(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer|exists:assign_tasks,id', // Task ID validation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        // Fetch the student assign task data
        $assignTaskQuery = StudentAssignTaskStatus::with('student')
            ->where('assign_task_id', $request->task_id)
            ->get();

        // Calculate total count

        // Group and count by assign_task_status
        $statusCounts = [
            'Total Count' => $assignTaskQuery->count(),
            'Pending Count' => $assignTaskQuery->where('assign_task_status', 1)->count(),
            'Completed Count' => $assignTaskQuery->where('assign_task_status', 2)->count(),
            'Late Count' => $assignTaskQuery->where('assign_task_status', 3)->count(),
        ];

        // Prepare response data
        $responseData = $assignTaskQuery->map(function ($detail) {
            return [
                'id' => $detail->id,
                'student_id' => $detail->student_id,
                'student_name' => $detail->student->student_name,
                'registration_no' => $detail->student->registration_no,
                'submission_date' => $detail->submission_date,
                'assign_task_status' => $detail->assign_task_status,
            ];
        });

        // Return response with total count and status-wise count
        return response()->json([
            'success' => true,
            'message' => 'Student List Retrieved Successfully !',
            'status_counts' => $statusCounts, // Custom counts with labels
            'data' => $responseData,
        ], 200);
    }
    public function subjectList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'company_location_id' => 'required|exists:company_locations,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        // Validate the teacher and their assignments
        $assignments = SubjectTeacherAssignment::where('teacher_id', $request->employee_id)
            ->where('company_location_id', $request->company_location_id)
            ->where('section_id', $request->section_id)
            ->with('subject')
            ->get();

        if ($assignments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You don\'t have access to see the subject list for this section',
                'data' => null
            ], 403);
        }



        // Prepare response data
        $responseData = $assignments->map(function ($detail) {
            return [
                'subject_id' => $detail->subject->id,
                'subject_name' => $detail->subject->subject_name,
            ];
        });

        // Return response with total count and status-wise count
        return response()->json([
            'success' => true,
            'message' => 'Subject List Retrieved Successfully !',
            'data' => $responseData,
        ], 200);
    }

    public function webupdate(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'assign_task_id' => 'required|integer|exists:assign_tasks,id', // Task ID validation
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'section_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        try {
            // Find the task by ID
            $task = AssignTask::findOrFail($request->assign_task_id);

            // Update the task with validated data
            $task->section_id = $request->section_id;
            $task->title = $request->title;
            $task->subject_id = $request->subject_id;
            $task->description = $request->description;
            $task->start_date = $request->start_date;
            $task->end_date = $request->end_date;

            // Save the task
            $task->save();

            return redirect()->route('assign-tasks.index')->with('success', 'Task assigned successfully and notifications sent!');
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the task.',
                'data' => null,
            ], 500);
        }
    }

}
