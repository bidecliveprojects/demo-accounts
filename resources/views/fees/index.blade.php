@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d');
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
			<?php echo CommonHelper::displayPrintButtonInBlade('PrintFeesList','','1');?>
			<button id="csv" onclick="generateCSVFile('ExportFeesList','View Fees List')" class="btn btn-sm btn-warning">TO CSV</button>
            <button id="pdf" onclick="generatePDFFile('ExportFeesList','View Fees List')" class="btn btn-sm btn-success">TO PDF</button>
		</div>
	</div>
	<div class="lineHeight">&nbsp;</div>
    <form id="list_data" method="get" action="{{ route('fees.index') }}">
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
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>From Date</label>
                <input type="date" name="filter_from_date" id="filter_from_date" value="{{$fromDate}}" class="form-control" />
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>&nbsp;</label>
                <input type="text" class="form-control" readonly value="Between" />
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>To Date</label>
                <input type="date" name="filter_to_date" id="filter_to_date" value="{{$toDate}}" class="form-control" />
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-print">
                                {{CommonHelper::displayPageTitle('View Fees List')}}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print">
                                <a href="{{ route('fees.receipt-voucher-against-fees') }}" class="btn btn-success btn-xs">+ Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive wrapper">
                            <table class="table table-responsive table-bordered" id="ExportFeesList">
                                {{CommonHelper::displayPDFTableHeader('6','View Fees List')}}
                                <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Registration No</th>
                                        <th class="text-center">Student Name</th>
                                        <th class="text-center">Father Name</th>
                                        <th class="text-center">Month-Year</th>
                                        <th class="text-center">Amount</th>
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