@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
    <div class="well_N">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('PrintLoansList', '', '1'); ?>
                <button id="csv" onclick="generateCSVFile('ExportLoansList','View Employee List')"
                    class="btn btn-sm btn-warning">TO CSV</button>
                <button id="pdf" onclick="generatePDFFile('ExportLoansList','View Employee List')"
                    class="btn btn-sm btn-success">TO PDF</button>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="boking-wrp dp_sdw" id="PrintEmployeesList">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-print">
                    {{ CommonHelper::displayPageTitle('View Loan List') }}
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print">
                    <a href="{{ route('loan.create') }}" class="btn btn-success btn-xs">+ Create New</a>
                </div>
            </div>
            <form id="list_data" method="get" action="{{ route('loan.index') }}">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Status</label>
                        <select name="filterStatus" id="filterStatus" class="form-control select2">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="2">InActive</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="padding: 30px;">
                        <input type="button" id="filter-button" value="Filter" onclick="dataCall()"
                            class="btn btn-xs btn-success" />
                    </div>
                </div>
            </form>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive wrapper">
                                <table class="table table-responsive table-bordered data-table" id="ExportLoansList">
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
        dataCall();
    </script>
@endsection
