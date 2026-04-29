@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d');
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card" id="PrintEmployeeAttendanceList">
        <div class="row hr-page-head hidden-print">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('View Employee Attendance List') }}
                <p class="hr-page-lead text-muted hidden-xs">Filter attendance by date range. Export or print when needed.</p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                {!! CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList', '', '1') !!}
                <div class="btn-group hr-export-group" role="group" aria-label="Export">
                    <button type="button" id="csv" onclick="generateCSVFile('ExportEmployeeAttendanceList','View Employee Attendance List')"
                        class="btn btn-default btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</button>
                    <button type="button" id="pdf" onclick="generatePDFFile('ExportEmployeeAttendanceList','View Employee Attendance List')"
                        class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                </div>
                <a href="{{ route('attendances.import') }}" class="btn btn-success btn-sm"><i class="fa fa-upload" aria-hidden="true"></i> Import attendance</a>
            </div>
        </div>
        <form id="list_data" method="get" action="{{ route('attendances.index') }}" class="hr-filter-form">
            <div class="row filter-toolbar-actions hr-filter-row">
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label for="filter_from_date">From date</label>
                    <input type="date" name="filter_from_date" id="filter_from_date" value="{{ $fromDate }}" class="form-control" />
                </div>
                <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12 hr-between-wrap">
                    <label class="hr-between-label">Range</label>
                    <div class="hr-between-badge" title="Date range">↔</div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label for="filter_to_date">To date</label>
                    <input type="date" name="filter_to_date" id="filter_to_date" value="{{ $toDate }}" class="form-control" />
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 hr-filter-submit-wrap">
                    <button type="button" onclick="get_ajax_data()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                </div>
            </div>
        </form>
        <div class="hr-table-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed table-hover hr-data-table" id="ExportEmployeeAttendanceList">
                    {{ CommonHelper::displayPDFTableHeader('1000','View Employee Attendance List') }}
                    <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Emp No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Clock In</th>
                            <th class="text-center">Clock Out</th>
                            <th class="text-center">Total Hours</th>
                            <th class="text-center">Late Status</th>
                            <th class="text-center">Absent Status</th>
                        </tr>
                    </thead>
                    <tbody id="data"></tbody>
                </table>
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
