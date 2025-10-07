<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicDetail;
use App\Models\AcademicStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AcademicDetailController extends Controller
{
    //
    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'company_location_id' => 'required|exists:company_locations,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }
        try {


            // Retrieve all academic details for the current school and campus
            $academicDetails = AcademicDetail::where('company_location_id', $request->company_location_id)
                ->with('academicStatus:id,name') // Load related academic status
                ->get();

            // Group academic details by month
            $groupedByMonth = $academicDetails->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->start_date)->format('F'); // Group by month name
            });

            // Transform the response
            $responseData = [];
            foreach ($groupedByMonth as $month => $details) {
                $responseData[$month] = $details->map(function ($detail) {
                    return [
                        'title' => $detail->title,
                        'academic_status' => $detail->academicStatus->name ?? 'N/A',
                        'start_date' => \Carbon\Carbon::parse($detail->start_date)->format('d'), // Extract only the day
                    ];
                });
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Academic details retrieved successfully!',
                'data' => $responseData,
            ], 200);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching academic details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function academicstatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_location_id' => 'required|exists:company_locations,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }

        $responseData =  AcademicStatus::where('company_location_id' , $request->company_location_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Academic Status retrieved successfully!',
            'data' => $responseData,
        ], 200);
    }

    public function listAcademicDetailsByMonth(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'company_location_id' => 'required|exists:company_locations,id',
            'month' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December', // Validate month

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }
       

        try {
            // Get campus_id and month from request
            $campusId = $request->input('campus_id');
            $month = $request->input('month');

            // Convert the month name to a numeric value for filtering
            $monthNumber = Carbon::parse($month)->month;

            // Retrieve all academic details for the specific campus and month
            $academicDetails = AcademicDetail::where('company_location_id', $request->company_location_id)
                ->whereMonth('start_date', $monthNumber) // Filter by month
                ->with('academicStatus:id,name') // Load related academic status
                ->get();

            // Transform the data
            $responseData = $academicDetails->map(function ($detail) {
                return [
                    'title' => $detail->title,
                    'status' => $detail->academicStatus->name ?? 'N/A',
                    'start_date' => Carbon::parse($detail->start_date)->format('Y-m-d'), // Extract date from datetime
                    'start_time' => Carbon::parse($detail->start_date)->format('H:i:s'), // Extract time from datetime
                    'end_date' => Carbon::parse($detail->end_date)->format('Y-m-d'), // Extract date from datetime
                    'end_time' => Carbon::parse($detail->end_date)->format('H:i:s'), // Extract time from datetime
                ];
            });

            // Return success response
            return response()->json([
                'success' => true,
                'message' => "Academic details for the month of $month retrieved successfully!",
                'data' => $responseData,
            ], 200);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching academic details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
