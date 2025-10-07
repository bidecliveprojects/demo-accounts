<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Roster;
use App\Models\RosterDetail;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\TimeTable;
use DB;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\SectionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;

class RoasterController extends Controller
{
    private $sectionRepository;

    public function __construct(SectionRepositoryInterface $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Roster Index Request:', $request->all());



        $sectionId = $request->input('section_id');
        $schoolId = Session::get('company_id');
        $schoolCampusId = Session::get('company_location_id');



        // Log the roster data for debugging
        if ($request->ajax()) {
            // Fetch the section details
            $section = Section::findOrFail($sectionId);

            // Days of the week
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            // Initialize an empty array to store roster data
            $rosterData = [];

            foreach ($days as $day) {
                // Fetch roster for the current day
                $roster = Roster::where('section_id', $sectionId)
                    ->where('day_of_week', $day)
                    ->where('company_id', $schoolId)
                    ->where('company_location_id', $schoolCampusId)
                    ->first();

                if ($roster) {
                    // Fetch all roster details (periods) for the current day
                    $rosterDetails = RosterDetail::where('roster_id', $roster->id)
                        ->with(['timeSlot', 'subjectTeacherAssignment.subject', 'subjectTeacherAssignment.teacher'])
                        ->orderBy('time_slot_id')
                        ->get();


                    // Store the details in the roster data array
                    $rosterData[$day] = $rosterDetails;
                } else {
                    $rosterData[$day] = collect([]); // Empty collection for days without rosters
                }
            }

            Log::info('Roster Data Retrieved Successfully', ['section_id' => $sectionId, 'data' => $rosterData]);

            return view('roaster.indexAjax', compact('rosterData'));
        }
        // Return the view with roster data
        return view('roaster.index');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roaster.create');
    }

    public function getTeacherSubjectAssignmentsForSection($section_id)
    {

        $response = DB::table('sections')
            ->join('subject_teacher_assignments', 'sections.id', '=', 'subject_teacher_assignments.section_id')
            ->join('subjects', 'subjects.id', '=', 'subject_teacher_assignments.subject_id')
            ->join('employees', 'employees.id', '=', 'subject_teacher_assignments.teacher_id')
            ->select(
                'subject_teacher_assignments.id',
                'sections.id as section_id',
                'sections.section_name',
                'subjects.id as subject_id',
                'subjects.subject_name',
                'employees.id as teacher_id',
                'employees.emp_name as teacher_name'
            )
            ->where('sections.id', '=', $section_id)
            ->get();

        \Log::debug('Section Data:', $response->toArray());

        // Return the response as JSON
        return response()->json($response);
    }


    public function getTimeSlots($section_id)
    {
        $timeslots = TimeSlot::where('section_id', $section_id)
            ->select('id', 'period_number', 'start_time', 'end_time')
            ->get();
        return response()->json($timeslots);

    }


    public function store(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Store Roster Request:', $request->all());
        $schoolId = Session::get('company_id');
        $schoolCampusId = Session::get('company_location_id');

        // Validation
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'monday_period_count' => 'nullable|integer|min:1|max:8',
            'tuesday_period_count' => 'nullable|integer|min:1|max:8',
            'wednesday_period_count' => 'nullable|integer|min:1|max:8',
            'thursday_period_count' => 'nullable|integer|min:1|max:8',
            'friday_period_count' => 'nullable|integer|min:1|max:8',
        ]);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $createdBy = Auth::user()->name ?? 'system';

        try {
            // Begin processing
            Log::info('Processing Roster Creation', ['section_id' => $request->section_id]);

            foreach ($days as $day) {
                $periodCount = $request->input("{$day}_period_count");

                if ($periodCount) {
                    // Log period count being processed
                    Log::info("Processing {$day} Roster", ['period_count' => $periodCount]);

                    // Create Roster entry
                    $roster = Roster::create([
                        'company_id' => $schoolId,
                        'company_location_id' => $schoolCampusId,
                        'section_id' => $request->section_id,
                        'day_of_week' => ucfirst($day),
                        'total_periods' => $periodCount,
                    ]);

                    Log::info("Roster created successfully", ['roster_id' => $roster->id]);

                    // Save details for each period
                    for ($i = 1; $i <= $periodCount; $i++) {
                        $subjectTeacherId = $request->input("{$day}_period_{$i}");

                        if ($subjectTeacherId) {
                            // Fetch the time_slot_id for the current section and period number
                            $timeSlot = TimeSlot::where('section_id', $request->section_id)
                                ->where('period_number', $i) // Match the period number
                                ->first();

                            if ($timeSlot) {
                                RosterDetail::create([
                                    'roster_id' => $roster->id,
                                    'time_slot_id' => $timeSlot->id, // Use the actual time_slot ID
                                    'subject_teacher_assignment_id' => $subjectTeacherId,
                                ]);

                                Log::info("RosterDetail created", [
                                    'roster_id' => $roster->id,
                                    'time_slot_id' => $timeSlot->id,
                                    'subject_teacher_assignment_id' => $subjectTeacherId,
                                ]);
                            } else {
                                Log::warning("Time slot not found", [
                                    'section_id' => $request->section_id,
                                    'period_number' => $i,
                                ]);
                            }
                        } else {
                            Log::warning("Missing subject_teacher_assignment_id", [
                                'day' => $day,
                                'period' => $i,
                            ]);
                        }
                    }
                }
            }

            Log::info('Roster creation completed successfully');
            return redirect()->route('roaster.index')->with('success', 'Roster created successfully.');

        } catch (\Exception $e) {
            // Log the error details
            Log::error('Error in Roster Creation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->with('error', 'An error occurred while creating the roster. Please check logs for details.');
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $section = $this->sectionRepository->findSection($id);

        return view('roaster.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_name' => 'required|string|max:255'
        ]);

        $this->sectionRepository->updateSection($data, $id);

        return redirect()->route('roaster.index')->with('message', 'Section Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->sectionRepository->changeSectionStatus($id, 2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id)
    {
        $this->sectionRepository->changeSectionStatus($id, 1);
        return response()->json(['success' => 'Active Successfully!']);
    }















}
