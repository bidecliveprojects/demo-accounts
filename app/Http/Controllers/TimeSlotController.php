<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectTeacherAssignment;
use App\Models\TimeSlot;
use DB;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\SectionRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Session;

class TimeSlotController extends Controller
{



    public function index(Request $request)
    {
        $sections = CommonHelper::get_all_sections(1);
        if ($request->ajax()) {
            // Fetch the section_id from the request (it's compulsory)
            Log::info('Time Slot Data');
            $sectionId = $request->input('section_id');

            // Ensure that section_id is provided; if not, return with an error or redirect
            if (!$sectionId) {
                return redirect()->route('timeslots.index')->with('error', 'Section ID is required.');
            }

            // Query the time slots for the given section (if section_id is provided)
            $timeslotsQuery = TimeSlot::where('section_id', $sectionId);

            // Get the timeslot data
            $timeslots = $timeslotsQuery->get();

            // If the request is an AJAX request, return only the table rows
            Log::info('Time Slot Data' . json_encode($timeslots));
            return view('timeslots.indexAjax', compact('timeslots'));
        }

        // Otherwise, return the full view with the timeslot data
        return view('timeslots.index', compact('sections'));
    }





    public function create()
    {
        //
        $sections = CommonHelper::get_all_sections(1);
        return view('timeslots.create', compact('sections'));
    }



    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'section_id' => 'required|exists:sections,id',  // Ensure section exists
            'time_slots.*.period_number' => 'required|integer',  // Period number is required
            'time_slots.*.start_time' => 'required|date_format:H:i',  // Start time format
            'time_slots.*.end_time' => 'required|date_format:H:i|after:start_time',  // End time must be after start time
            'time_slots.*.period_number' => [
                'required',
                'integer',
                Rule::unique('time_slots')->where(function ($query) use ($request) {
                    return $query->where('section_id', $request->input('section_id'));
                }),
            ],
        ], [
            'time_slots.*.end_time.after' => 'End time must be after start time for each period.',
            'time_slots.*.period_number.unique' => 'The period number must be unique for each section.',
        ]);

        try {
            // Begin a transaction to ensure all time slots are inserted properly
            DB::beginTransaction();

            // Get the school and campus ID from the session
            $schoolId = Session::get('company_id');
            $schoolCampusId = Session::get('company_location_id');

            // Retrieve the section_id from the request
            $sectionId = $request->input('section_id');

            // Iterate over the time slots and store them
            foreach ($request->input('time_slots') as $timeSlot) {
                TimeSlot::create([
                    'company_id' => $schoolId,
                    'company_location_id' => $schoolCampusId,
                    'section_id' => $sectionId,
                    'period_number' => $timeSlot['period_number'],
                    'start_time' => $timeSlot['start_time'],
                    'end_time' => $timeSlot['end_time'],
                ]);
            }

            // Commit the transaction
            \DB::commit();

            // Redirect back to the timeslot list with success message
            return redirect()->route('timeslots.index')
                ->with('success', 'Time slots created successfully.');

        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();
            // Redirect back with error message
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    public function edit($id)
    {
        // Retrieve the time slot data by ID
        $timeSlot = TimeSlot::findOrFail($id);
        $schoolId = Session::get('company_id');
        $schoolCampusId = Session::get('company_location_id');
        // Retrieve all sections for the section select dropdown
        $section = Section::where('company_id', $schoolId)
            ->where('company_location_id', $schoolCampusId)
            ->where('id', $timeSlot->section_id)
            ->first();

        // Pass the time slot data and sections to the edit view
        return view('timeslots.edit', compact('timeSlot', 'section'));
    }



    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'period_number' => 'required|integer',  // Period number is required
            'start_time' => 'required|date_format:H:i',  // Start time format
            'end_time' => 'required|date_format:H:i|after:start_time',  // End time must be after start time
            'period_number' => [
                'required',
                'integer',
                Rule::unique('time_slots')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('section_id', $request->input('section_id'));
                }),
            ],
        ], [
            'end_time.after' => 'End time must be after start time.',
            'period_number.unique' => 'The period number must be unique for the selected section.',
        ]);

        try {
            // Begin a transaction to ensure the update is successful
            DB::beginTransaction();

            // Retrieve the time slot by ID
            $timeSlot = TimeSlot::findOrFail($id);

            // Update the time slot data
            $timeSlot->update([
                'period_number' => $request->input('period_number'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
            ]);

            // Commit the transaction
            DB::commit();

            // Redirect back to the timeslot list with a success message
            return redirect()->route('timeslots.index')
                ->with('success', 'Time slot updated successfully.');

        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();
            // Redirect back with an error message
            return redirect()->back()->with('error', $e->getMessage());
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $assignment = TimeSlot::where('id', $id)->update(['status' => 2]);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id)
    {
        $assignment = TimeSlot::where('id', $id)->update(['status' => 1]);
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

