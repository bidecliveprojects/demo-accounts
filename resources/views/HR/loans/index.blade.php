@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
    <div class="well_N">
        <div class="boking-wrp dp_sdw hr-page-card" id="PrintLoansList">
            <div class="row hr-page-head hidden-print">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    {{ CommonHelper::displayPageTitle('View Loan List') }}
                    <p class="hr-page-lead text-muted hidden-xs">Filter loans by status. Export or open a record below.</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                    {!! CommonHelper::displayPrintButtonInBlade('PrintLoansList', '', '1') !!}
                    <div class="btn-group hr-export-group" role="group" aria-label="Export">
                        <button type="button" id="csv" onclick="generateCSVFile('ExportLoansList','View Loan List')"
                            class="btn btn-default btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</button>
                        <button type="button" id="pdf" onclick="generatePDFFile('ExportLoansList','View Loan List')"
                            class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                    </div>
                    <a href="{{ route('loan.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New loan</a>
                </div>
            </div>
            <form id="list_data" method="get" action="{{ route('loan.index') }}" class="hr-filter-form">
                <div class="row filter-toolbar-actions hr-filter-row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <label for="filterStatus">Status</label>
                        <select name="filterStatus" id="filterStatus" class="form-control select2">
                            <option value="">All statuses</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 hr-filter-submit-wrap">
                        <button type="button" id="filter-button" onclick="dataCall()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                    </div>
                </div>
            </form>
            <div class="hr-table-wrap">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover hr-data-table data-table" id="ExportLoansList">
                        <thead>
                            {{ CommonHelper::displayPDFTableHeader('1000', 'View Loan List') }}
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Emp Name</th>
                                <th class="text-center">Apply Loan Date</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Per Month Deduction</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Status</th>
                                <th class="text-center hidden-print">Action</th>
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
        function dataCall() {
            var columnTable = [{
                    data: 'id',
                    title: 'S.No',
                    class: 'text-center'
                },
                {
                    data: 'emp_name',
                    title: 'Emp Name',
                    class: 'text-center'
                },
                {
                    data: 'apply_loan_date',
                    title: 'Apply Loan Date',
                    class: 'text-center'
                },
                {
                    data: 'amount',
                    title: 'Amount',
                    class: 'text-center'
                },
                {
                    data: 'per_month_deduction',
                    title: 'Per Month Deduction',
                    class: 'text-center'
                },
                {
                    data: 'description',
                    title: 'Description',
                    class: 'text-center'
                },
                {
                    data: 'status',
                    title: 'Status',
                    class: 'text-center'
                },
                {
                    data: 'action',
                    title: 'Action',
                    class: 'text-center hidden-print'
                }
            ];
            get_ajax_data_two('ExportLoansList', columnTable);
        }
        $(document).ready(function() {
            dataCall();
        });
    </script>
@endsection
