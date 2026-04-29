@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d', strtotime('+30 days'));
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
	<div class="boking-wrp dp_sdw finance-page-card">
	    <div class="row finance-page-head">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('View Purchase Payment Voucher List') }}
                <p class="finance-page-lead text-muted hidden-xs">Filter purchase payments by type, status, and dates.</p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right finance-page-actions">
                <a href="{{ route('purchase-payments.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New payment</a>
            </div>
        </div>
        <form id="list_data" method="get" action="{{ route('purchase-payments.index') }}" class="finance-filter-form">
            <div class="row filter-toolbar-actions finance-filter-row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <label for="filterVoucherType">Voucher type</label>
                    <select name="filterVoucherType" id="filterVoucherType" class="form-control select2">
                        <option value="">All vouchers</option>
                        <option value="1">Cash</option>
                        <option value="2">Bank</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <label for="filterStatus">Status</label>
                    <select name="filterStatus" id="filterStatus" class="form-control select2">
                        <option value="">All statuses</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label for="from_date">From date</label>
                    <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}" class="form-control" />
                </div>
                <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12 finance-between-wrap">
                    <label class="finance-between-label">Range</label>
                    <div class="finance-between-badge" title="Date range">↔</div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label for="to_date">To date</label>
                    <input type="date" name="to_date" id="to_date" value="{{ $toDate }}" class="form-control" />
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 finance-filter-submit-wrap">
                    <button type="button" onclick="get_ajax_data()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                </div>
            </div>
        </form>
        <div class="finance-table-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed table-hover finance-data-table">
                    <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">P.V.No.</th>
                            <th class="text-center">P.V.Date</th>
                            <th class="text-center">Voucher Type</th>
                            <th class="text-center">Cheque No</th>
                            <th class="text-center">Cheque Date</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Account Detail</th>
                            <th class="text-center">Created Detail</th>
                            <th class="text-center">Voucher Status</th>
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
                $('#filterVoucherType, #filterStatus').select2({ width: '100%' });
            }
            get_ajax_data();
        });
    </script>
@endsection
