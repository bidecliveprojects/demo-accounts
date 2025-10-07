<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    protected $isApi;
    public function __construct(Request $request)
    {
        // Check if the request is for API
        $this->isApi = $request->is('api/*');
        // Define the base view path
        $this->page = 'announcements.';
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

    public function show($id){
        $annoucementDetail = Announcement::where('id',$id)->with([
            'employee',
            'subject',
            'section'
        ])->first();
        if (is_null($annoucementDetail)) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Annoucement Not Found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Annoucement Detail Retrieved successfully',
            'data' => $annoucementDetail,
        ], 200);
    }

    public function todayLatestAnnoucement(Request $request)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'date' => 'required|date',
                'company_id' => 'required|integer',
                'company_location_id' => 'required|integer',
                'employee_id' => 'nullable|integer',
                'section_id' => 'nullable|integer',
                'subject_id' => 'nullable|integer',
                'annoucement_type' => 'nullable|integer|in:1,2,3',
                'publish' => 'nullable|integer|in:1,2',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Build the query dynamically with relationships
        $query = Announcement::with(['employee', 'subject', 'section'])
            ->where('created_date', $validatedData['date']);

        // Apply filters if present
        collect(['publish', 'employee_id', 'section_id', 'subject_id', 'annoucement_type'])
            ->each(function ($field) use ($validatedData, $query) {
                if (!empty($validatedData[$field])) {
                    $query->where($field, $validatedData[$field]);
                }
            });

        // Fetch the latest announcement
        $announcement = $query->latest('created_date')->first();

        // Check if a record exists
        if (!$announcement) {
            return response()->json([
                'status' => 'error',
                'message' => 'No announcements found for the given criteria',
            ], 404);
        }

        // Return the response
        return response()->json([
            'status' => 'success',
            'message' => 'Latest Announcement Retrieved Successfully',
            'data' => $announcement,
        ], 200);
    }


    public function index(Request $request)
    {
        if($request->ajax() || $this->isApi){
            try {
                // Validate incoming request data
                $validatedData = $request->validate([
                    'company_id' => 'required|integer',
                    'company_location_id' => 'required|integer',
                    'employee_id' => 'nullable|integer',
                    'section_id' => 'nullable|integer',
                    'subject_id' => 'nullable|integer',
                    'annoucement_type' => 'nullable|integer|in:1,2,3',
                    'publish' => 'nullable|integer|in:1,2',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            // Start query with relationships
            $query = Announcement::with(['employee', 'subject', 'section']);

            // Apply filters dynamically
            $filterableFields = [
                'publish',
                'employee_id',
                'section_id',
                'subject_id',
                'annoucement_type',
            ];

            foreach ($filterableFields as $field) {
                if (!empty($validatedData[$field])) {
                    $query->where($field, $validatedData[$field]);
                }
            }

            // Get the filtered results
            $annoucements = $query->get();

            // Return the response with the filtered announcements
            if (!$this->isApi) {
                return $this->webResponse('indexAjax', compact('annoucements'));
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Announcements Retrieved Successfully',
                'data' => $annoucements,
            ], 200);
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

    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validatedData = $request->validate([
                'company_id' => 'required|integer',
                'company_location_id' => 'required|integer',
                'employee_id' => 'required|integer',
                'section_id' => 'required|integer',
                'subject_id' => 'required|integer',
                'annoucement_type' => 'required|integer|in:1,2,3',
                'publish' => 'required|integer|in:1,2',
                'title' => 'required|string',
                'description' => 'required|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Prepare data for insertion
        $dataToInsert = [
            'company_id' => $validatedData['company_id'],
            'company_location_id' => $validatedData['company_location_id'],
            'employee_id' => $validatedData['employee_id'],
            'section_id' => $validatedData['section_id'] ?? null,
            'subject_id' => $validatedData['subject_id'],
            'annoucement_type' => $validatedData['annoucement_type'],
            'publish' => $validatedData['publish'] ?? null,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'created_date' => now()->format('Y-m-d'),
            'status' => 1,
            'created_by' => Auth::user()->name ?? 'System'
        ];

        try {
            // Insert task into the database
            DB::table('annoucements')->insertGetId($dataToInsert);

            if (!$this->isApi) {
                return redirect()->route($this->page.'index')->with('success', 'Annoucement Created successfully');
            }

            return response()->json([
                'success' => true,
                'message' => 'Annoucement Created successfully!',
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error Annoucement: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to annoucement. Please try again later.',
            ], 500);
        }
    }
}
