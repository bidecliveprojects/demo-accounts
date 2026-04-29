@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d', strtotime('+30 days'));
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card" id="PrintSalesReportList">
        <div class="row hr-page-head hidden-print">
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('Sales Report') }}
                <p class="hr-page-lead text-muted hidden-xs">Sales by product variant for the selected range.</p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                {!! CommonHelper::displayPrintButtonInBlade('PrintSalesReportList', '', '1') !!}
                <button type="button" id="pdf" onclick="generatePDFFile('ExportSalesReportList','View Sales Report List')" class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
            </div>
        </div>
        <form id="list_data" method="get" action="{{ route('reports.viewSalesReport') }}" class="hr-filter-form">
            <div class="row filter-toolbar-actions hr-filter-row">
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <input type="hidden" value="{{ Session::get('company_id') }}" id="company_id" name="company_id" />
                    <input type="hidden" value="{{ Session::get('company_location_id') }}" id="company_location_id" name="company_location_id" />
                    <label for="filter_product_variant_id">Product / variant</label>
                    <select name="filter_product_variant_id" id="filter_product_variant_id" class="form-control select2">
                        @foreach($products as $product)
                            <optgroup label="{{ $product['name'] }}">
                                @foreach($product['variants'] as $variant)
                                    <option value="{{ $variant['id'] }}">
                                        {{ $variant['size_name'] }} — {{ number_format($variant['amount'], 2) }}
                                    </option>
                                @endforeach
                            </optgroup>
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
                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 hr-filter-submit-wrap">
                    <button type="button" onclick="get_ajax_data()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
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
            get_ajax_data_for_report();
        });
    </script>
@endsection
