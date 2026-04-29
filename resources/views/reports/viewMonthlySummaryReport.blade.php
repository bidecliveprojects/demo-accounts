@php
    use App\Helpers\CommonHelper;
    use Illuminate\Support\Facades\DB;
    $companyLogoRow = DB::table('companies')->where('id', Session::get('company_id'))->first();
    $reportsLogoUrl = ($companyLogoRow && ! empty($companyLogoRow->school_logo) && file_exists($companyLogoRow->school_logo))
        ? url($companyLogoRow->school_logo)
        : url('assets/img/no_image.png');
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card" id="PrintDashboardDetail">
        <div class="row hr-page-head hidden-print">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('Monthly Summary Report') }}
                <p class="hr-page-lead text-muted hidden-xs">Pick a month to load teacher and payroll dashboard figures.</p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                {!! CommonHelper::displayPrintButtonInBlade('PrintDashboardDetail', '', '1') !!}
            </div>
        </div>
        <div class="reports-ms-toolbar hr-filter-form">
            <div class="row filter-toolbar-actions hr-filter-row align-items-end">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-lg-offset-0">
                    <label for="month_year">Period</label>
                    <input type="month" name="month_year" id="month_year" value="{{ date('Y-m') }}" onchange="loadMonthlySummaryReportDetail()" class="form-control" />
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 reports-ms-logo-wrap hidden-print text-center text-md-left">
                    <span class="text-muted small hidden-xs">School logo</span>
                    <div class="reports-ms-logo">
                        <img src="{{ $reportsLogoUrl }}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div id="loadData" class="reports-ms-result"></div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function loadMonthlySummaryReportDetail(){
            var monthYear = $('#month_year').val();
            $("#loadData").html('<div class="col-lg-12"><div class="loader"></div></div>');
            $.ajax({
                url: '{{ route('reports.viewMonthlySummaryReport') }}',
                method: 'GET',
                data: {
                    monthYear: monthYear
                },
                error: function() {
                    alert('error');
                },
                success: function(response) {
                    $('#loadData').html(response);
                }
            });
        }
        $(document).ready(function() {
            loadMonthlySummaryReportDetail();
        });
    </script>
@endsection
