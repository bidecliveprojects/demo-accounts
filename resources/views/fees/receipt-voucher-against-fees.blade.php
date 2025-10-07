@php
    use App\Helpers\CommonHelper;
    $fromDate = date('Y-m-d', strtotime('-30 days'));
    $toDate = date('Y-m-d');
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <form id="list_data" method="get" action="{{ route('fees.receipt-voucher-against-fees') }}">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Student Name</label>
                <select name="filter_student_id" id="filter_student_id" class="form-control select2">
                    @if(empty(Session::get('login_cnic')))
                        <option value="">Select Student</option>
                    @endif
                    @foreach($getAllStudents as $gasRow)
                        <option value="{{$gasRow->id}}">{{$gasRow->registration_no}} - {{ $gasRow->student_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="padding: 30px;">
                <input type="button" value="Filter" onclick="get_ajax_data()" class="btn btn-xs btn-success" />
            </div>
        </div>
    </form>
    <div class="lineHeight">&nbsp;</div>
	<div class="boking-wrp dp_sdw" id="PrintFeesList">
	    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                {{CommonHelper::displayPageTitle('View Receipt Voucher List')}}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print">
                                <a href="{{ route('fees.index') }}" class="btn btn-success btn-xs">+ View List</a>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('fees.receipt-voucher-against-fees-store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="table-responsive wrapper">
                                <table class="table table-responsive table-bordered" id="ExportFeesList">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Registration No</th>
                                            <th class="text-center">Student Name</th>
                                            <th class="text-center">Father Name</th>
                                            <th class="text-center">Month-Year</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Debit Account</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
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
        function updateReceiptAmount(id){
            var balanceAmount = $('#balance_amount_'+id+'').val();
            var newReceiptAmount = $('#receipt_amount_'+id+'').val();
            if (parseInt(newReceiptAmount) <= parseInt(balanceAmount)) {
            } else {
                alert('Something went wrong! New Receipt Amount is Greater Than Balance Amount.......');
                $('#receipt_amount_'+id+'').val('');
                return false;
            }
        }

        function submitBtnDisableEnable(){

        }

    </script>
@endsection