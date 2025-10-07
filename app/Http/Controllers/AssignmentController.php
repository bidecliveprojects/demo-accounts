<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\ClassTeacherAssignments;
use App\Models\Employee;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectTeacherAssignment;
use DB;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\SectionRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Session;

class AssignmentController extends Controller
{



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $schoolId = Session::get('company_id');
            $schoolCampusId = Session::get('company_location_id');

            // Get section_id from the request, and ensure it is provided
            $sectionId = $request->input('section_id');

            if (!$sectionId) {
                // If section_id is missing, return an error response
                return response()->json(['error' => 'Section ID is required'], 400);
            }

            // Initialize query to get the section for the provided section_id
            $section = DB::table('sections')
                ->where('company_id', $schoolId)
                ->where('company_location_id', $schoolCampusId)
                ->where('id', $sectionId)
                ->first();
            Log::info('Check no 1: ' . json_encode($section));



            if (!$section) {
                return response()->json(['error' => 'Section not found'], 404);
            }

            // Fetch the subjects assigned to the provided section
            $subjects = DB::table('subjects')
                ->join('subject_teacher_assignments', function ($join) use ($section, $schoolId, $schoolCampusId) {
                    $join->on('subjects.id', '=', 'subject_teacher_assignments.subject_id')
                        ->where('subject_teacher_assignments.company_id', '=', $schoolId)
                        ->where('subject_teacher_assignments.company_location_id', '=', $schoolCampusId)
                        ->where('subject_teacher_assignments.section_id', '=', $section->id);
                })
                ->select(
                    'subjects.id as subject_id',
                    'subjects.subject_name',
                    'subject_teacher_assignments.teacher_id',
                    'subject_teacher_assignments.status',
                    'subject_teacher_assignments.id as assignment_id' // Include assignment ID
                )
                ->get();

            Log::info('Check no 2: ' . json_encode($subjects));

            $subjectsData = [];

            // Loop through each subject and fetch the assigned teachers along with the status and assignment IDs
            foreach ($subjects as $subject) {
                // Fetch the teacher(s) for this subject assignment
                $teachers = DB::table('employees')
                    ->join('subject_teacher_assignments', 'employees.id', '=', 'subject_teacher_assignments.teacher_id')
                    ->where('subject_teacher_assignments.subject_id', '=', $subject->subject_id)
                    ->where('subject_teacher_assignments.section_id', '=', $section->id)
                    ->where('subject_teacher_assignments.company_id', '=', $schoolId)
                    ->where('subject_teacher_assignments.company_location_id', '=', $schoolCampusId)
                    ->select('employees.emp_name as teacher_name')
                    ->get();

                // Add the subject, teachers, assignment IDs, and status to the data array
                $subjectsData[] = [
                    'subject' => $subject->subject_name,
                    'teachers' => $teachers->pluck('teacher_name')->toArray(), // Get only teacher names as an array
                    'status' => $subject->status, // Include the status
                    'assignment_id' => $subject->assignment_id // Include the assignment ID
                ];
            }

            // Prepare the final data to pass to the view
            $assignments = [
                'section' => $section->section_name, // Just one section since it's required
                'subjects' => $subjectsData
            ];

            Log::info('Check no 3: ' . json_encode($assignments));

            // Return the data as a JSON response
            return view('assignments.indexAjax', compact('assignments'));
        }

        // If it's not an AJAX request, just return the default view
        return view('assignments.index');
    }




    public function create()
    {
        //
        $sections = CommonHelper::get_all_sections(1);
        $teachers = Employee::where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->where('emp_type', 2)
            ->where('status', 1)
            ->select('id', 'emp_name')
            ->get();
        return view('assignments.create', compact('sections', 'teachers'));
    }



    public function store(Request $request)
    {
        try {
            // Start a transaction
            DB::beginTransaction();

            // Validate the form input
            $request->validate([
                'section_id' => 'required|exists:sections,id',
                'subjects' => 'required|array',
                'subjects.*' => 'required|exists:employees,id',
                'class_teacher_id' => 'required|exists:employees,id'
            ]);

            // Get the school and campus ID from the session
            $schoolId = Session::get('company_id');
            $schoolCampusId = Session::get('company_location_id');

            // Get the section from the database
            $section = Section::find($request->section_id);
            if (!$section) {
                return redirect()->back()->with('error', 'Section not found');
            }



            $existingclassAssignment = ClassTeacherAssignments::where('company_id', $schoolId)->where('company_location_id', $schoolCampusId)->where('teacher_id', $request->class_teacher_id)->where('section_id', $request->section_id, )->exists();

            if ($existingclassAssignment) {
                return redirect()->back()->with('error', "Class Teacher is already assigned for '$section->classes->class_name' - '$section->section_name' ");
            }
            // Create class teacher assignment
            ClassTeacherAssignments::create([
                'company_id' => $schoolId,
                'company_location_id' => $schoolCampusId,
                'section_id' => $request->section_id,
                'teacher_id' => $request->class_teacher_id,
            ]);

            // Loop through the subjects and their assigned teacher IDs
            foreach ($request->subjects as $subjectId => $teacherId) {
                // Check if a duplicate entry exists for the same section and subject
                $existingAssignment = SubjectTeacherAssignment::where('section_id', $section->id)
                    ->where('subject_id', $subjectId)
                    ->where('company_id', $schoolId)
                    ->where('company_location_id', $schoolCampusId)
                    ->first();

                if ($existingAssignment) {
                    // Fetch the subject name
                    $subjectName = Subject::find($subjectId)->subject_name ?? 'Unknown Subject';

                    // Fetch the teacher name from the existing assignment
                    $existingTeacher = Employee::find($existingAssignment->teacher_id);
                    $teacherName = $existingTeacher ? $existingTeacher->emp_name : 'Unknown Teacher';

                    return redirect()->back()->with('error', "The subject '$subjectName' is already assigned to the teacher '$teacherName' for this section.");
                }

                // Ensure the subject exists and is active
                $subject = Subject::where('id', $subjectId)
                    ->where('company_id', $schoolId)
                    ->where('company_location_id', $schoolCampusId)
                    ->where('status', 1) // Only active subjects
                    ->first();

                // Ensure the teacher exists and is active
                $teacher = Employee::where('id', $teacherId)
                    ->where('company_id', $schoolId)
                    ->where('company_location_id', $schoolCampusId)
                    ->where('status', 1) // Only active teachers
                    ->first();

                if ($subject && $teacher) {
                    // Store the assignment in the subject_teacher pivot table
                    $subject->teachers()->attach($teacherId, [
                        'section_id' => $section->id,
                        'company_id' => $schoolId,
                        'company_location_id' => $schoolCampusId,
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Invalid subject or teacher assignment.');
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('assignments.index')->with('success', 'Subjects assigned to teachers successfully.');
        } catch (\Throwable $th) {
            // Rollback the transaction in case of error
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }



    public function edit($id)
    {
        // Fetch the assignment by ID
        $assignment = SubjectTeacherAssignment::find($id);

        if (!$assignment) {
            return redirect()->route('assignments.index')->with('error', 'Assignment not found.');
        }

        // Get related data for dropdowns or selections
        $schoolId = Session::get('company_id');
        $schoolCampusId = Session::get('company_location_id');

        $section = Section::where('company_id', $schoolId)
            ->where('company_location_id', $schoolCampusId)
            ->where('id', $assignment->section_id)
            ->first();

        $subjects = Subject::where('section_id', $assignment->section_id)
            ->where('company_id', $schoolId)
            ->where('company_location_id', $schoolCampusId)
            ->where('status', 1) // Active subjects
            ->select('id', 'subject_name')
            ->get();

        $teachers = Employee::where('company_id', $schoolId)
            ->where('company_location_id', $schoolCampusId)
            ->where('status', 1)
            ->get();

        // Pass the data to the view
        return view('assignments.edit', compact('assignment', 'section', 'subjects', 'teachers'));
    }


    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:employees,id',
        ]);

        // Fetch the assignment record
        $assignment = SubjectTeacherAssignment::find($id);

        // Log the fetched assignment data for debugging
        Log::info('Assignment Data: ' . json_encode($assignment));

        if (!$assignment) {
            return redirect()->route('assignments.index')->with('error', 'Assignment not found.');
        }

        // Fetch the section_id from the existing assignment
        $section_id = $assignment->section_id;

        // Check if a record with the same section_id and subject_id already exists
        $existingAssignment = SubjectTeacherAssignment::where('section_id', $section_id)
            ->where('subject_id', $validated['subject_id'])
            ->where('id', '!=', $id) // Exclude the current record being updated
            ->first();

        if ($existingAssignment) {
            return redirect()->back()->with('error', 'The selected subject is already assigned to a teacher.');
        }

        // Update the assignment with the new data
        $assignment->update([
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
        ]);

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $assignment = SubjectTeacherAssignment::where('id', $id)->update(['status' => 2]);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id)
    {
        $assignment = SubjectTeacherAssignment::where('id', $id)->update(['status' => 1]);
        return response()->json(['success' => 'Active Successfully!']);
    }




    public function getSubjects($section_id)
    {
        // Get the school and campus ID from the session
        $schoolId = Session::get('company_id');
        $schoolCampusId = Session::get('company_location_id');
        log::info($schoolId);
        Log::info($schoolCampusId);
        Log::info($section_id);

        // Find the section by its ID
        $section = Section::find($section_id);

        Log::info($section);

        if ($section) {
            // Fetch active subjects based on the section_id, company_id, and company_location_id
            $subjects = Subject::where('section_id', $section_id)
                ->where('company_id', $schoolId)
                ->where('company_location_id', $schoolCampusId)
                ->where('status', 1) // Active subjects
                ->select('id', 'subject_name')
                ->get();



            // Fetch active teachers based on company_id, company_location_id, and status
            $teachers = Employee::where('company_id', $schoolId)
                ->where('company_location_id', $schoolCampusId)
                ->where('emp_type', 2)
                ->where('status', 1)
                ->select('id', 'emp_name')
                ->get();

            // Return the filtered subjects and teachers
            return response()->json([
                'subjects' => $subjects,
                'teachers' => $teachers
            ]);
        }

        // Return empty arrays if the section is not found
        return response()->json([
            'subjects' => [],
            'teachers' => []
        ]);
    }









}

