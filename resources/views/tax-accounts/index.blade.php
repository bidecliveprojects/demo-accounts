@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card" id="PrintTaxAccountsList">
        <div class="row hr-page-head hidden-print">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('View Tax Accounts List') }}
                <p class="hr-page-lead text-muted hidden-xs">Tax codes and rates — filter by status or add an account.</p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                {!! CommonHelper::displayPrintButtonInBlade('PrintTaxAccountsList', '', '1') !!}
                <div class="btn-group hr-export-group" role="group" aria-label="Export">
                    <button type="button" id="csv" onclick="generateCSVFile('ExportTaxAccountsList','View Tax Accounts List')" class="btn btn-default btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</button>
                    <button type="button" id="pdf" onclick="generatePDFFile('ExportTaxAccountsList','View Tax Accounts List')" class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                </div>
                <a href="{{ route('tax-accounts.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New tax account</a>
            </div>
        </div>
        <form id="list_data" method="get" action="{{ route('tax-accounts.index') }}" class="hr-filter-form">
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
                    <button type="button" onclick="get_ajax_data()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                </div>
            </div>
        </form>
        <div class="hr-table-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover hr-data-table" id="ExportTaxAccountsList">
                    {{ CommonHelper::displayPDFTableHeader('1000','View Tax Accounts List') }}
                    <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Account Code</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Percent Value</th>
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
        $(document).ready(function() {
            get_ajax_data();
        });
    </script>
@endsection
