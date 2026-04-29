@php
    use App\Helpers\CommonHelper;
    use Illuminate\Support\Facades\Session;

    $schoolId = Session::get('company_id');
    $schoolCampusId = Session::get('company_location_id');
    $companyLocations = CommonHelper::getCompanyLocations($schoolId);

    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d', strtotime('+30 days'));
@endphp

@extends('layouts.layouts')

@section('custom-css-end')
<style>
    .dashboard-page .dashboard-print-wrap { margin-top: 8px; }
    @media (min-width: 768px) {
        .dashboard-page .dashboard-print-wrap { margin-top: 0; }
    }
</style>
@endsection

@section('content')
<div class="dashboard-page well_N">
    <div class="row dashboard-toolbar">
        <div class="col-xs-12 col-sm-8">
            <h1 class="dashboard-page-title">Dashboard</h1>
            <p class="dashboard-page-lead text-muted">
                Select location and date range to view payments, receipts, invoices, and profit — all in one place.
            </p>
        </div>
        <div class="col-xs-12 col-sm-4 text-right dashboard-print-wrap">
            {!! CommonHelper::displayPrintButtonInBlade('PrintDashboardDetail', '', '1') !!}
        </div>
    </div>

    <div class="boking-wrp dp_sdw dashboard-filter-shell">
        <div class="row" id="PrintDashboardDetail">
            <div class="col-xs-12 dashboard-filter-block">
                <div class="dashboard-filter-heading">
                    <span class="dashboard-filter-icon"><i class="fa fa-sliders" aria-hidden="true"></i></span>
                    <span class="dashboard-filter-title">Report filters</span>
                </div>
                <p class="dashboard-filter-hint text-muted">
                    Pick a campus and period. Quick ranges set dates for you; then press <strong>Apply</strong> to refresh figures.
                </p>

                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12 mb-2">
                        <label for="company_location">Location</label>
                        <select name="company_location" id="company_location" class="form-control select2" title="Company location">
                            @foreach ($companyLocations as $id => $name)
                                <option value="{{ $id }}" {{ (string) $id === (string) $schoolCampusId ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
                        <label for="from_date">From date</label>
                        <input type="date" id="from_date" class="form-control" value="{{ $fromDate }}" autocomplete="off">
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
                        <label for="to_date">To date</label>
                        <input type="date" id="to_date" class="form-control" value="{{ $toDate }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-12 col-xs-12 mb-2 dashboard-apply-col">
                        <label class="dashboard-apply-label">&nbsp;</label>
                        <button type="button" id="dashboard-apply-btn" class="btn btn-primary btn-block" onclick="loadDashboardSummaryDetail()">
                            <i class="fa fa-refresh" aria-hidden="true"></i> Apply
                        </button>
                    </div>
                </div>

                <div class="dashboard-quick-ranges">
                    <span class="dashboard-quick-label text-muted">Quick ranges:</span>
                    <div class="btn-group btn-group-sm dashboard-quick-btns" role="group" aria-label="Date presets">
                        <button type="button" class="btn btn-default" onclick="dashboardDatePreset('today')">Today</button>
                        <button type="button" class="btn btn-default" onclick="dashboardDatePreset('7d')">7 days</button>
                        <button type="button" class="btn btn-default" onclick="dashboardDatePreset('30d')">30 days</button>
                        <button type="button" class="btn btn-default" onclick="dashboardDatePreset('month')">This month</button>
                        <button type="button" class="btn btn-default" onclick="dashboardDatePreset('year')">This year</button>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 dashboard-data-wrap">
                <div id="loadData" class="dashboard-load-region">
                    <div class="dashboard-loading">
                        <div class="loader"></div>
                        <p class="text-muted text-center dashboard-loading-text">Loading dashboard…</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function dashboardPad2(n) {
        return (n < 10 ? '0' : '') + n;
    }

    function dashboardFormatYmd(d) {
        return d.getFullYear() + '-' + dashboardPad2(d.getMonth() + 1) + '-' + dashboardPad2(d.getDate());
    }

    /**
     * Presets: today | 7d | 30d | month | year
     */
    function dashboardDatePreset(preset) {
        var now = new Date();
        var to = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        var from = new Date(to);

        switch (preset) {
            case 'today':
                break;
            case '7d':
                from.setDate(from.getDate() - 6);
                break;
            case '30d':
                from.setDate(from.getDate() - 29);
                break;
            case 'month':
                from = new Date(now.getFullYear(), now.getMonth(), 1);
                break;
            case 'year':
                from = new Date(now.getFullYear(), 0, 1);
                break;
            default:
                return;
        }

        $('#from_date').val(dashboardFormatYmd(from));
        $('#to_date').val(dashboardFormatYmd(to));
        loadDashboardSummaryDetail();
    }

    function loadDashboardSummaryDetail() {
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        var companyLocation = $('#company_location').val();
        var $btn = $('#dashboard-apply-btn');

        if (fromDate && toDate && fromDate > toDate) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid date range',
                    text: 'From date cannot be after To date. Please adjust and try again.'
                });
            } else {
                alert('From date cannot be after To date.');
            }
            return;
        }

        var originalHtml = $btn.data('original-html');
        if (!originalHtml) {
            originalHtml = $btn.html();
            $btn.data('original-html', originalHtml);
        }
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading…');

        $('#loadData').html(
            '<div class="dashboard-loading">' +
            '<div class="loader"></div>' +
            '<p class="text-muted text-center dashboard-loading-text">Loading dashboard…</p>' +
            '</div>'
        );

        $.ajax({
            url: "{{ route('dashboard') }}",
            method: 'GET',
            data: { fromDate: fromDate, toDate: toDate, companyLocation: companyLocation },
            success: function(response) {
                $('#loadData').html(response);
            },
            error: function(xhr) {
                var msg = 'Could not load dashboard data. Please try again.';
                if (xhr.status === 401 || xhr.status === 419) {
                    msg = 'Session expired. Please refresh the page and sign in again.';
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                } else {
                    alert(msg);
                }
                $('#loadData').html('<div class="alert alert-danger dashboard-error-banner">' + msg + '</div>');
            },
            complete: function() {
                $btn.prop('disabled', false).html($btn.data('original-html'));
            }
        });
    }

    $(document).ready(function() {
        loadDashboardSummaryDetail();
    });
</script>
@endsection
