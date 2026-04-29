@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d', strtotime('+30 days'));
@endphp

@extends('layouts.layouts')

@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card" id="PrintTrialBalanceReport">
        <div class="row hr-page-head hidden-print">
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('Trial Balance Report') }}
                <p class="hr-page-lead text-muted hidden-xs">Debit and credit totals by account for the period.</p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                {!! CommonHelper::displayPrintButtonInBlade('PrintTrialBalanceReport', '', '1') !!}
                <button type="button" id="pdf" onclick="generatePDFFile('PrintTrialBalanceReport','Trial Balance Report')" class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
            </div>
        </div>
        <form id="list_data" method="get" action="{{ route('reports.viewTrialBalanceReport') }}" class="hr-filter-form">
            <div class="row filter-toolbar-actions hr-filter-row">
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <label for="entry_type_id">Transaction type</label>
                    <select name="entry_type_id" id="entry_type_id" class="form-control select2">
                        <option value="1">All Locations</option>
                        <option value="2">Individual Location</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <input type="hidden" value="{{ Session::get('company_id') }}" id="company_id" name="company_id" />
                    <input type="hidden" value="{{ Session::get('company_location_id') }}" id="company_location_id" name="company_location_id" />
                    <label for="acc_id">Account</label>
                    <select name="acc_id" id="acc_id" class="form-control select2">
                        @foreach(CommonHelper::get_all_chart_of_account() as $row)
                            <option value="{{ $row->id }}">{{ $row->code }} — {{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label for="from_date">From date</label>
                    <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}" class="form-control" />
                </div>
                <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12 hr-between-wrap">
                    <label class="hr-between-label">Range</label>
                    <div class="hr-between-badge" title="Date range">↔</div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label for="to_date">To date</label>
                    <input type="date" name="to_date" id="to_date" value="{{ $toDate }}" class="form-control" />
                </div>
                <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 hr-filter-submit-wrap">
                    <button type="button" onclick="get_ajax_data_for_trial_balance()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                </div>
            </div>
        </form>
        <div class="hr-table-wrap reports-ajax-result-wrap">
            <div id="data"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        get_ajax_data_for_trial_balance();
    });

    function get_ajax_data_for_trial_balance() {
        $.ajax({
            url: "{{ route('reports.viewTrialBalanceReport') }}",
            data: $("#list_data").serialize(),
            type: 'GET',
            success: function(data) {
                $("#data").html(data);
            },
            error: function(){
                alert('Failed to fetch trial balance data.');
            }
        });
    }
</script>
@endsection
