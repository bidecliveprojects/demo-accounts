<?php

namespace App\Http\Controllers;

use App\Models\AcademicDetail;
use App\Models\AcademicStatus;
use App\Models\Subject;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\AcademicDetailRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class AcademicDetailController extends Controller
{
    private $academicDetailRepository;

    public function __construct(AcademicDetailRepositoryInterface $academicDetailRepository)
    {
        $this->academicDetailRepository = $academicDetailRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $academicstatus = $this->academicDetailRepository->allAcademicDetail($request->all());
            Log::info("Hello MyWorldsvcds" . $academicstatus);
            return view('academicdetail.indexAjax', compact('academicstatus'));
        }
        return view('academicdetail.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $AcademicStatus = AcademicStatus::where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->status(1)->get();
        return view('academicdetail.create', compact('AcademicStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'academic_status_id' => 'required|exists:academic_status,id',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check for date conflicts
        $conflictingEvent = AcademicDetail::where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
            ->first();

        if ($conflictingEvent) {
            // Return response if conflict is found
            return back()->withErrors([
                'error' => 'The provided date and time conflict with an existing event: ' . $conflictingEvent->title,
            ]);
        }



        // Store the new academic detail
        $this->academicDetailRepository->storeAcademicDetail($data);

        // Redirect to index page with success message
        return redirect()->route('academic.detail.index')->with('message', 'AcademicStatus Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $academicdetail = $this->academicDetailRepository->findAcademicDetail($id);
        $AcademicStatus = AcademicStatus::where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->status(1)->get();

        return view('academicdetail.edit', compact('AcademicStatus', 'academicdetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'academic_status_id' => 'required|exists:academic_status,id',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check for conflicting dates
        $conflict = AcademicDetail::where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->where('id', '!=', $id) // Exclude the current record
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
            ->first();

        if ($conflict) {
            return back()->withErrors([
                'error' => 'The provided date and time conflict with an existing event: ' . $conflict->title,
            ]);
        }

        // Perform the update
        $this->academicDetailRepository->updateAcademicDetail($data, $id);

        return redirect()->route('academic.detail.index')->with('message', 'AcademicStatus Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->academicDetailRepository->changeAcademicDetailStatus($id, 2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id)
    {
        $this->academicDetailRepository->changeAcademicDetailStatus($id, 1);
        return response()->json(['success' => 'Active Successfully!']);
    }
}
