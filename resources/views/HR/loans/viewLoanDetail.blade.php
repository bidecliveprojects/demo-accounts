@php
    use App\Helpers\CommonHelper;
    $counterA = 1;
@endphp
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeesDetail','','1');?>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
<div class="well" id="PrintEmployeesDetail">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    @if(file_exists($employeeDetail->emp_image))
                        <img src="{{$employeeDetail->emp_image}}" class="rounded" alt="Lights" style="width:200px; height: 150px">
                    @else
                        <img src="'.url('assets/img/no_image.png').'" class="rounded" alt="Lights" style="width:200px; height: 150px">
                    @endif
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <h4><span class="modalTitle">{{$employeeDetail->emp_no}} - {{$employeeDetail->emp_name}}</span></h4>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <h4><span class="modalTitle">
                        @if($employeeDetail->emp_type == 2)
                            Teaching Staff
                        @elseif($employeeDetail->emp_type == 3)
                            Nazim
                        @elseif($employeeDetail->emp_type == 4)
                            Naib Nazim
                        @elseif($employeeDetail->emp_type == 5)
                            Moavin
                        @else
                            Non Teaching Staff
                        @endif
                    </span></h4>
                </div>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th colspan="8">Employee Detail</th>
                        </tr>
                    </thead>
                    <tbdoy>
                        <tr>
                            <th>Father Name</th>
                            <td>{{$employeeDetail->emp_father_name}}</td>
                            <th>Date of Birth</th>
                            <td>{{$employeeDetail->date_of_birth}}</td>
                            <th>CNIC No</th>
                            <td>{{$employeeDetail->cnic_no}}</td>
                            <th>Email</th>
                            <td>{{$employeeDetail->emp_email}}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td colspan="7">{{$employeeDetail->address}}</td>
                        </tr>
                        <tr>
                            <th>Phone No</th>
                            <td>{{$employeeDetail->phone_no}}</td>
                            <th>Marital Status</th>
                            <td>{{$employeeDetail->maritarial_status == 1 ? 'Maried' : 'Unmaried'}}</td>
                            <th>Job Type</th>
                            <td>{{ $employeeDetail->job_type == 1 ? 'Full Time' : 'Part Time' }}</td>
                            <th>Employement Status</th>
                            <td>{{$employeeDetail->employment_status == 1 ? 'Permanent' : 'Contract Base'}}</td>
                        </tr>
                        <tr>
                            <th>Number of Children</th>
                            <td>{{$employeeDetail->no_of_childern}}</td>
                            <th>Reference Name</th>
                            <td>{{$employeeDetail->relative_name}}</td>
                            <th>Reference Contact No</th>
                            <td>{{$employeeDetail->relative_contact_no}}</td>
                            <th>Reference Address</th>
                            <td>{{$employeeDetail->relative_address}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th colspan="4">Employee Experience Detail</th>
                        </tr>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Organization Name</th>
                            <th class="text-center">Reason of Resign</th>
                            <th class="text-center">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($employeeExperiences->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">No records found</td>
                            </tr>
                        @else
                            @foreach($employeeExperiences as $eeRow)
                                <tr>
                                    <td class="text-center">{{ $counterA++ }}</td>
                                    <td>{{ $eeRow->organization_name }}</td>
                                    <td>{{ $eeRow->reason_of_resign }}</td>
                                    <td>{{ $eeRow->duration }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @php
                $counter = 0; // Initialize a counter
            @endphp
            <div class="row">
                @foreach($employeeDocuments as $edRow)
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        {{ CommonHelper::display_document($edRow->document_path) }}
                    </div>
                    @php
                        $counter++; // Increment the counter
                        // Check if we've added 12 items, if so, close the current row and start a new one
                        if ($counter % 4 == 0 && !$loop->last) {
                            echo '</div><div class="lineHeight">&nbsp;</div><div class="row">'; // Close and start a new row
                        }
                    @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>