@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')

@section('title', 'Cash Flow Statement')

@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card reports-cash-flow-print" id="PrintCashFlowStatement">
        <div class="row hr-page-head hidden-print">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('Cash Flow Statement') }}
                <p class="hr-page-lead text-muted hidden-xs">Operating, investing, and financing cash flows for the period.</p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                {!! CommonHelper::displayPrintButtonInBlade('PrintCashFlowStatement', '', '1') !!}
            </div>
        </div>
        <form method="GET" action="{{ route('reports.cash-flow-statement') }}" class="hr-filter-form">
            <div class="row filter-toolbar-actions hr-filter-row">
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <label for="entry_type_id">Transaction type</label>
                    <select name="entry_type_id" id="entry_type_id" class="form-control select2">
                        <option value="1">All Locations</option>
                        <option value="2">Individual Location</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label for="from_date">From date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12 hr-between-wrap">
                    <label class="hr-between-label">Range</label>
                    <div class="hr-between-badge" title="Date range">↔</div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label for="to_date">To date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 hr-filter-submit-wrap">
                    <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                </div>
            </div>
        </form>

        <div class="hr-table-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-hover hr-data-table reports-cash-flow-table">
                    <tbody>
                        <tr class="reports-cash-flow-section">
                            <th colspan="2">Cash flow from operating activities</th>
                        </tr>
                        <tr>
                            <td>Inflows</td>
                            <td class="text-end">{{ number_format($operatingInflows, 3) }}</td>
                        </tr>
                        <tr>
                            <td>Outflows</td>
                            <td class="text-end">({{ number_format($operatingOutflows, 3) }})</td>
                        </tr>
                        <tr class="reports-cash-flow-subtotal">
                            <th>Net operating cash flow</th>
                            <th class="text-end">{{ number_format($operatingInflows - $operatingOutflows, 3) }}</th>
                        </tr>

                        <tr class="reports-cash-flow-section">
                            <th colspan="2">Cash flow from investing activities</th>
                        </tr>
                        <tr>
                            <td>Inflows</td>
                            <td class="text-end">{{ number_format($investingInflows, 3) }}</td>
                        </tr>
                        <tr>
                            <td>Outflows</td>
                            <td class="text-end">({{ number_format($investingOutflows, 3) }})</td>
                        </tr>
                        <tr class="reports-cash-flow-subtotal">
                            <th>Net investing cash flow</th>
                            <th class="text-end">{{ number_format($investingInflows - $investingOutflows, 3) }}</th>
                        </tr>

                        <tr class="reports-cash-flow-section">
                            <th colspan="2">Cash flow from financing activities</th>
                        </tr>
                        <tr>
                            <td>Inflows</td>
                            <td class="text-end">{{ number_format($financingInflows, 3) }}</td>
                        </tr>
                        <tr>
                            <td>Outflows</td>
                            <td class="text-end">({{ number_format($financingOutflows, 3) }})</td>
                        </tr>
                        <tr class="reports-cash-flow-subtotal">
                            <th>Net financing cash flow</th>
                            <th class="text-end">{{ number_format($financingInflows - $financingOutflows, 3) }}</th>
                        </tr>

                        <tr class="reports-cash-flow-total">
                            <th>Total net cash flow</th>
                            <th class="text-end">{{ number_format($netCashFlow, 3) }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
