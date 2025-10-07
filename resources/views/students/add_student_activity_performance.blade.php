@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d');
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <form id="list_data" method="get" action="{{ route('add-student-activity-performance') }}">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Student Name</label>
                <select name="filter_student_id" id="filter_student_id" class="form-control select2">
                    @if(empty(Session::get('login_cnic')))
                        <option value="">Select Student</option>
                    @endif
                    @foreach($getAllStudents as $gasRow)
                        <option value="{{$gasRow->id}}">{{$gasRow->registration_no}} - {{ $gasRow->student_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="padding: 30px;">
                <input type="button" value="Filter" onclick="get_ajax_data()" class="btn btn-xs btn-success" />
            </div>
        </div>
    </form>
    <div class="lineHeight">&nbsp;</div>
	<div class="boking-wrp dp_sdw" id="PrintFeesList">
	    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                {{CommonHelper::displayPageTitle('Add Student Activity Performance')}}
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('add-student-activity-performance-store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="table-responsive wrapper">
                                <table class="table table-responsive table-bordered" id="ExportFeesList">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Registration No</th>
                                            <th class="text-center">Student Name</th>
                                            <th class="text-center">Sabqi Para</th>
                                            <th class="text-center">Sabqi Para Performance</th>
                                            <th class="text-center">Manzil Performance</th>
                                            @foreach($heads as $hRow)
                                                <th class="text-center">{{$hRow->head_name}}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            get_ajax_data();
        });
        

    </script>
@endsection