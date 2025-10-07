<?php

namespace App\Http\Controllers;

use App\Models\LevelOfPerformance;
use Illuminate\Http\Request;
use App\Http\Requests\LevelOfPerformanceRequest;

use App\Repositories\Interfaces\LevelOfPerformanceRepositoryInterface;

class LevelOfPerformanceController extends Controller
{
    private $levelOfPerformanceRepository;

    public function __construct(LevelOfPerformanceRepositoryInterface $levelOfPerformanceRepository)
    {
        $this->levelOfPerformanceRepository = $levelOfPerformanceRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $levelofperformances =  $this->levelOfPerformanceRepository->allLevelOfPerformances($request->all());

            return view('levelOfPerformance.indexAjax', compact('levelofperformances'));
        }
        return view('levelOfPerformance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('levelOfPerformance.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LevelOfPerformanceRequest $request)
    {
        $this->levelOfPerformanceRepository->storeLevelOfPerformance($request->all());

        return redirect()->route('levelofperformance.index')->with('message', 'Level Of Performance Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LevelOfPerformance  $levelOfPerformance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LevelOfPerformance  $levelOfPerformance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $performance = $this->levelOfPerformanceRepository->findLevelOfPerformance($id);

        return view('levelOfPerformance.edit', compact('performance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LevelOfPerformance  $levelOfPerformance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'performance_name' => 'required|string|max:255'
        ]);

        $this->levelOfPerformanceRepository->updateLevelOfPerformance($data, $id);

        return redirect()->route('levelofperformance.index')->with('message', 'Level Of Performance Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LevelOfPerformance  $levelOfPerformance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $this->levelOfPerformanceRepository->changeLevelOfPerformanceStatus($id,2);
        return response()->json(['success' => 'Inactive Successfully!']);
    }

    public function changeInactiveToActiveRecord($id){
        $this->levelOfPerformanceRepository->changeLevelOfPerformanceStatus($id,1);
        return response()->json(['success' => 'Active Successfully!']);
    }
}
