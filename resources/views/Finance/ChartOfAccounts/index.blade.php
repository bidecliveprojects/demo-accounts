@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
	<div class="boking-wrp dp_sdw finance-page-card">
	    <div class="row finance-page-head">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('View Chart of Account List') }}
                <p class="finance-page-lead text-muted hidden-xs">Browse accounts and filter by status.</p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right finance-page-actions">
                <a href="{{ route('chartofaccounts.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New account</a>
            </div>
        </div>
        <form id="list_data" method="get" action="{{ route('chartofaccounts.index') }}" class="finance-filter-form">
            <div class="row filter-toolbar-actions finance-filter-row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <label for="filterStatus">Status</label>
                    <select name="filterStatus" id="filterStatus" class="form-control select2">
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                        <option value="">All statuses</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 finance-filter-submit-wrap">
                    <button type="button" onclick="get_ajax_data()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                </div>
            </div>
        </form>
        <div class="finance-table-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover finance-data-table">
                    <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Account Name</th>
                            <th class="text-center">Parent Account</th>
                            <th class="text-center">Account Type</th>
                            <th class="text-center">Action</th>
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
            if ($.fn.select2) {
                $('#filterStatus').select2({ width: '100%' });
            }
            get_ajax_data();
        });
    </script>
@endsection
