@php
    use App\Helpers\CommonHelper;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo CommonHelper::displayPrintButtonInBlade('PrintFeesVoucherDetail','','1');?>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
<div id="PrintFeesVoucherDetail">
    <style>
        .voucherCompanyClass{
            border-top: 1px solid;
            border-bottom: 1px solid;
            padding: 11px;
            font-size: 20px;
            font-weight: bold;
        }

        .voucherHeadingClass{
            border-top: 1px solid;
            border-bottom: 1px solid;
            padding: 11px;
            font-size: 17px;
            font-weight: bold;
        }
        .floatLeft{
            width: 48%;
            float: left;
        }
        .floatRight{
            width: 48%;
            float: right;
        }
    </style>
    @for ($i = 0; $i < 2; $i++)
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <p class="voucherCompanyClass">{{Session::get('company_name')}}</p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <p class="voucherHeadingClass">Receipt Voucher / Fee Challan</p>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="floatLeft">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th>Registration No:</th>
                                        <td>{{$feesDetail->registration_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{$feesDetail->student_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Department:</th>
                                        <td>{{$feesDetail->department_name}}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="floatRight">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th>Father Name:</th>
                                        <td>{{$feesDetail->father_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Receipt No:</th>
                                        <td>{{$feesDetail->fee_registration_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>Month Of:</th>
                                        <td>{{$feesDetail->month_year}}</td>
                                    </tr>
                                    
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Receipt Amount</th>
                                    <td class="text-right">{{number_format($feesDetail->amount,0)}}</td>
                                    <th>Balance Amount</th>
                                    <td class="text-right">{{number_format($feesDetail->totalPaymentAmount - $feesDetail->totalReceiptAmount,0)}}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="lineHeight">&nbsp;</div>
                <div class="lineHeight">&nbsp;</div>
                <div class="lineHeight">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p style="border-bottom: 1px solid #000;"><strong>Paid By</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-1">&nbsp;</div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p style="border-bottom: 1px solid #000;"><strong>Accounts</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">---------------------------------------------------------------------------------------------</div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="lineHeight">&nbsp;</div>
    @endfor
</div>