@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
			<?php echo CommonHelper::displayPrintButtonInBlade('PrintStudentsList','','1');?>
			<button id="csv" onclick="generateCSVFile('ExportStudentsList','View Student List')" class="btn btn-sm btn-warning">TO CSV</button>
            <button id="pdf" onclick="generatePDFFile('ExportStudentsList','View Student List')" class="btn btn-sm btn-success">TO PDF</button>
		</div>
	</div>
	<div class="lineHeight">&nbsp;</div>
    <form id="list_data" method="get" action="{{ route('students.index') }}">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Department</label>
                <select name="filter_department_id" id="filter_department_id" class="form-control select2">
                    <option value="">Select Department</option>
                    @foreach (CommonHelper::get_all_departments() as $row)
                        <option value="{{ $row->id }}">{{ $row->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label>Status</label>
                <select name="filterStatus" id="filterStatus" class="form-control select2">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="2">InActive</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12" style="padding: 30px;">
                <input type="button" id="filter-button" value="Filter" onclick="dataCall()" class="btn btn-xs btn-success" />
            </div>
        </div>
    </form>
    <div class="lineHeight">&nbsp;</div>
	<div class="boking-wrp dp_sdw" id="PrintStudentsList">
	    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-print">
                                {{CommonHelper::displayPageTitle('View Student List')}}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print">
                                <a href="{{ route('students.create') }}" class="btn btn-success btn-xs">+ Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive wrapper">
                            <table class="table table-responsive table-bordered" id="ExportStudentsList">
                                <thead>
                                    {{CommonHelper::displayPDFTableHeader('19','View Student List')}}
                                    <tr>
                                        <th class="text-center sticky-col first-col">S.No</th>
                                        <th class="text-center sticky-col second-col hidden-print">Action</th>
                                        <th class="text-center sticky-col third-col">Registration No</th>
                                        <th class="text-center sticky-col fourth-col">Student Name</th>
                                        <th class="text-center sticky-col fifth-col">Passport Size Photo</th>
                                        <th class="text-center">Date of Admission</th>

                                        <th class="text-center">Father Name</th>
                                        <th class="text-center">Parent Email</th>
                                        <th class="text-center">Mobile Number</th>
                                        <th class="text-center">CNIC No</th>

                                        <th class="text-center">Birth Certificate</th>
                                        <th class="text-center">Father Guardian Cnic</th>
                                        <th class="text-center">Father Guardian Cnic Back</th>
                                        <th class="text-center">Copy Of Last Report</th>

                                        <th class="text-center">Department</th>
                                        <th class="text-center">Class Timing</th>
                                        <th class="text-center">Fees</th>
                                        <th class="text-center">Concession Fees</th>
                                        <th class="text-center">Concession Fees Image</th>
                                        <th class="text-center">Class Teacher Name</th>
                                        <th class="text-center">Status</th>
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
        function dataCall(){
            var baseUrl = '{{ url("/") }}';
            var columnTable = [
                { data: 'id', title: 'S.No' }, // Assuming 'id' is your S.No
                { data: 'action', title: 'Action', className: 'hidden-print' }, // Action column
                { data: 'registration_no', title: 'Registration No' },
                { data: 'student_name', title: 'Student Name' },
                { data: 'passport_size_photo', title: 'Passport Size Photo', render: function(data) {
                    return data ? `<img src="${data}" alt="Photo" style="width:50px; height:50px;" />` :
                        '<img src="assets/img/no_image.png" alt="Default Image" style="width:50px; height:50px;" />';
                }},
                { data: 'date_of_admission', title: 'Date of Admission' },
                { data: 'father_name', title: 'Father Name' },
                { data: 'parent_email', title: 'Parent Email' },
                { data: 'mobile_no', title: 'Mobile Number' },
                { data: 'cnic_no', title: 'CNIC No' },
                { data: 'birth_certificate', title: 'Birth Certificate', render: function(data) {
                    return data ? `<img src="${data}" alt="Birth Certificate" style="width:50px; height:50px;" />` :
                        '<img src="assets/img/no_image.png" alt="Default Image" style="width:50px; height:50px;" />';
                } },
                { data: 'father_guardian_cnic', title: 'Father Guardian Cnic', render: function(data) {
                    return data ? `<img src="${data}" alt="Father Guardian Cnic" style="width:50px; height:50px;" />` :
                        '<img src="assets/img/no_image.png" alt="Default Image" style="width:50px; height:50px;" />';
                } },
                { data: 'father_guardian_cnic_back', title: 'Father Guardian Cnic Back', render: function(data) {
                    return data ? `<img src="${data}" alt="Father Guardian Cnic" style="width:50px; height:50px;" />` :
                        '<img src="assets/img/no_image.png" alt="Default Image" style="width:50px; height:50px;" />';
                } },
                { data: 'copy_of_last_report', title: 'Copy Of Last Report', render: function(data) {
                    return data ? `<img src="${data}" alt="Copy Of Last Report" style="width:50px; height:50px;" />` :
                        '<img src="assets/img/no_image.png" alt="Default Image" style="width:50px; height:50px;" />';
                } },
                { data: 'department_name', title: 'Department' },
                { data: 'class_timing', title: 'Class Timing' },
                { data: 'fees', title: 'Fees' },
                { data: 'concession_fees', title: 'Concession Fees' },
                { data: 'concession_fees_image', title: 'Concession Fees Image', render: function(data) {
                    return data ? `<img src="${data}" alt="Concession Fee Image" style="width:50px; height:50px;" />` :
                        '<img src="assets/img/no_image.png" alt="Default Image" style="width:50px; height:50px;" />';
                }},
                { data: 'emp_name', title: 'Class Teacher Name' },
                { data: 'status', title: 'Status', render: function(data) {
                    return data === 1 ? 'Active' : 'Inactive'; // Example conversion of status code
                }}
            ];
            get_ajax_data_two('ExportStudentsList',columnTable);
        }
        dataCall();
    </script>
@endsection
