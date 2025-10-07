@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d');
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
			<?php echo CommonHelper::displayPrintButtonInBlade('PrintGenerateFeeVoucherList','','1');?>
			<button id="csv" onclick="generateCSVFile('ExportGenerateFeeVoucherList','View Generate Fee Voucher List')" class="btn btn-sm btn-warning">TO CSV</button>
            <button id="pdf" onclick="generatePDFFile('ExportGenerateFeeVoucherList','View Generate Fee Voucher List')" class="btn btn-sm btn-success">TO PDF</button>
		</div>
	</div>
	<div class="lineHeight">&nbsp;</div>
    <form id="list_data" method="get" action="{{ route('fees.generate-fee-voucher-list') }}">
        <div class="row hidden">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="padding: 30px;">
                <input type="button" value="Filter" onclick="get_ajax_data()" class="btn btn-xs btn-success" />
            </div>
        </div>
    </form>
    <div class="lineHeight">&nbsp;</div>
	<div class="boking-wrp dp_sdw" id="PrintGenerateFeeVoucherList">
	    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-print">
                                {{CommonHelper::displayPageTitle('View Generate Fee Voucher List')}}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print">
                                <a href="{{ route('fees.generate-fee-voucher') }}" class="btn btn-success btn-xs">+ Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive wrapper">
                            <table class="table table-responsive table-bordered" id="ExportGenerateFeeVoucherList">
                                {{CommonHelper::displayPDFTableHeader('4','View Generate Fee Voucher List')}}
                                <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Month-Year</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Amount</th>
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
        $(document).ready(function() {
            get_ajax_data();
        });
    </script>
@endsection