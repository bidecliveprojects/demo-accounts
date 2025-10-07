@php
    use App\Helpers\CommonHelper;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo CommonHelper::displayPrintButtonInBlade('PrintStudentAttendanceDetail','','1');?>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
<div id="PrintStudentAttendanceDetail">
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
    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <p class="voucherCompanyClass">{{Session::get('company_name')}}</p>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <p class="voucherHeadingClass">Student Attendance Detail</p>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="floatLeft">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th>Registration No:</th>
                                    <td>{{$studentInformation->registration_no}}</td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>{{$studentInformation->student_name}}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{$studentInformation->department_name}}</td>
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
                                    <td>{{$studentInformation->father_name}}</td>
                                </tr>
                                <tr>
                                    <th>Mobile No:</th>
                                    <td>{{$studentInformation->mobile_no}}</td>
                                </tr>
                                <tr>
                                    <th>Employee Name:</th>
                                    <td>{{$studentInformation->emp_name}}</td>
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
                                <th class="text-center">S.No</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Attendance Type</th>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Attendance Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $a = 0;
                                $counter = 1;
                                $noOfPresent = 0;
                                $noOfLeaves = 0;
                                $noOfHolidays = 0;
                            @endphp
                            <tr>
                            @foreach($studentAttendanceDetail as $sadRow)
                                @php
                                    $a = $a+1;
                                    if($sadRow->performance_activity_type == 3){
                                        $attendanceType = 'Holiday';
                                        $noOfHolidays += 1;
                                    }else if($sadRow->performance_activity_type == 2){
                                        $attendanceType = 'Leave';
                                        $noOfLeaves += 1;
                                    }else{
                                        $attendanceType = 'Present';
                                        $noOfPresent += 1;
                                    }
                                @endphp
                                    <td class="text-center">{{$counter++}}</td>
                                    <td>{{$sadRow->performance_date}}</td>
                                    <td>{{$attendanceType}}</td>
                                @if($a == 2)
                                </tr><tr>
                                @php
                                    $a = 0;
                                @endphp
                            @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total Presents: {{$noOfPresent}}</th>
                                <th colspan="2">Total Leaves: {{$noOfLeaves}}</th>
                                <th colspan="2">Total Holidays: {{$noOfHolidays}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>