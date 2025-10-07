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

        img {
            width: 15%;
            border-radius: 50%; /* Makes the image round */
        }
    </style>
    <div class="well">
        <div class="row">
            @for ($i = 0; $i < 3; $i++)
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if($i == 0) Bank Copy @elseif($i == 1) School Copy @else Parent Copy @endif
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <div>
                                <p class="voucherHeadingClass">{{ Session::get('company_name') }}</p>
                            </div>
                            <img style="width:15%;" src="{{ CommonHelper::displaySchoolLogo() }}">
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead style="background: #F2F2F2 ">
                                        <tr>
                                            <th colspan="2">Registration No:</th>
                                            <td colspan="2">{{$getData->registration_no}}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Name:</th>
                                            <td colspan="2">{{$getData->student_name}}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Father Name:</th>
                                            <td colspan="2">{{$getData->father_name}}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Month Of:</th>
                                            <td colspan="2">{{$getData->month_year}}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead style="background: #F2F2F2 ">
                                        <tr>
                                            <th class="text-center">Detail</th>
                                            <th class="text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- <tr>
                                            <td>Remaining Privious Month Fees</td>
                                            <td class="text-right">{{number_format($getData->totalPaymentAmount - $getData->totalReceiptAmount,0)}}</td>
                                        </tr> -->
                                        <tr>
                                            <td>Current Month Fees</td>
                                            <td class="text-right">{{number_format($getData->amount,0)}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-right">{{number_format($getData->amount,0)}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="voucherHeadingClass">Particulars</p>
                        </div>
                        <?php /*?><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <!-- {!! CommonHelper::settingDetail()->fee_voucher_footer_description ?? '-' !!} -->
                        </div><?php */?>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>