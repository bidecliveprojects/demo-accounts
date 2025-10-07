@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
			<?php echo CommonHelper::displayPrintButtonInBlade('PrintCompletedParasList','','1');?>
			<button id="csv" onclick="generateCSVFile('ExportCompletedParasList','View Completed Paras List')" class="btn btn-sm btn-warning">TO CSV</button>
            <button id="pdf" onclick="generatePDFFile('ExportCompletedParasList','View Completed Paras List')" class="btn btn-sm btn-success">TO PDF</button>
		</div>
	</div>
	<div class="lineHeight">&nbsp;</div>
    <form id="list_data" method="get" action="{{ route('comletedParasList') }}">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Student name</label>
                <select name="filter_student_id" id="filter_student_id" class="form-control select2">
                    @if(empty(Session::get('login_cnic')))
                        <option value="">Select Student</option>
                    @endif
                    @foreach (CommonHelper::get_all_students() as $row)
                        <option value="{{ $row->id }}">{{$row->registration_no}} - {{ $row->student_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="padding: 30px;">
                <input type="button" value="Filter" onclick="get_ajax_data()" class="btn btn-xs btn-success" />
            </div>
        </div>
    </form>
    <div class="lineHeight">&nbsp;</div>
	<div class="boking-wrp dp_sdw" id="PrintCompletedParasList">
	    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hidden-print">
                                {{CommonHelper::displayPageTitle('View Completed Paras List')}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive wrapper">
                            <table class="table table-responsive table-bordered" id="ExportCompletedParasList">
                                {{CommonHelper::displayPDFTableHeader('12','View Completed Paras List')}}
                                <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Student Name</th>
                                        <th class="text-center">Para Name</th>
                                        <th class="text-center">Total Lines in Para</th>
                                        <th class="text-center">Excellent</th>
                                        <th class="text-center">Good</th>
                                        <th class="text-center">Average</th>
                                        <th class="text-center">Need Attention</th>
                                        <th class="text-center">No of Days</th>
                                        <th class="text-center">No of Holidays</th>
                                        <th class="text-center">No of Leaves</th>
                                        <th class="text-center">Total Days</th>
                                        <th class="text-center hidden-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                </tbody>
                            </table>
                        </div>
                    </div>
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
