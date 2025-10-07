@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
			<?php echo CommonHelper::displayPrintButtonInBlade('PrintClassesList','','1');?>
			<button id="csv" onclick="generateCSVFile('ExportClassesList','View Class List')" class="btn btn-sm btn-warning">TO CSV</button>
            <button id="pdf" onclick="generatePDFFile('ExportClassesList','View Class List')" class="btn btn-sm btn-success">TO PDF</button>
		</div>
	</div>
    <div class="lineHeight">&nbsp;</div>
    <form id="list_data" method="get" action="{{ route('classes.index') }}">
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
                <input type="button" value="Filter" onclick="get_ajax_data()" class="btn btn-xs btn-success" />
            </div>
        </div>
    </form>
    <div class="lineHeight">&nbsp;</div>
	<div class="boking-wrp dp_sdw" id="PrintClassesList">
	    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-print">
                                {{CommonHelper::displayPageTitle('View Class List')}}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print ForSearch">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 text-right hidden-print">
                                        <a href="{{ route('classes.create') }}" class="btn btn-success btn-xs">+
                                            Create
                                            New</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print colSearch">
                                        <div class="searchBar">
                                            <input type="text" id="search" class="form-control pr-4"
                                                placeholder="Search for a classes..." onkeyup="get_ajax_data()">
                                            <i class="fa fa-search search-icon"
                                                style="right: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive wrapper">
                        <table class="table table-responsive table-bordered" id="ExportClassesList">
                            {{CommonHelper::displayPDFTableHeader('5','View Class List')}}
                            <thead style="background: #F2F2F2 ">
                                <th class="text-center">S.No</th>
                                <th class="text-center">Class No</th>
                                <th class="text-center">Class Name</th>
                                <th class="text-center">Fees Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center hidden-print">Action</th>
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
