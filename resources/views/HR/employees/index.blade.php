@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
    <div class="well_N hr-employees-module">
        <div class="boking-wrp dp_sdw hr-employees-panel hr-page-card" id="PrintEmployeesList">
            <div class="row hr-employees-head hr-page-head hidden-print">
                <div class="col-md-5 col-sm-12 hr-employees-title-col">
                    {{ CommonHelper::displayPageTitle('View Employee List') }}
                    <p class="hr-employees-lead text-muted">Filter by type, department, or status. Export or open a record below.</p>
                </div>
                <div class="col-md-7 col-sm-12 text-right hr-employees-actions">
                    <div class="hr-employees-actions-inner">
                        {!! CommonHelper::displayPrintButtonInBlade('PrintEmployeesList', '', '1') !!}
                        <div class="btn-group hr-employees-export-group" role="group" aria-label="Export">
                            <button type="button" id="csv" onclick="generateCSVFile('ExportEmployeesList','View Employee List')"
                                class="btn btn-default btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</button>
                            <button type="button" id="pdf" onclick="generatePDFFile('ExportEmployeesList','View Employee List')"
                                class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                        </div>
                        <a href="{{ route('employees.create') }}" class="btn btn-success btn-sm hr-employees-btn-create"><i class="fa fa-plus" aria-hidden="true"></i> New employee</a>
                    </div>
                </div>
            </div>

            <form id="list_data" method="get" action="{{ route('employees.index') }}" class="hr-employees-filters hr-filter-form">
                <div class="row filter-toolbar-actions employee-form-page hr-filter-row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="filterEmpType">Employee type</label>
                        <select name="filterEmpType" id="filterEmpType" class="form-control select2">
                            <option value="">All types</option>
                            <option value="1">Non-teaching staff</option>
                            <option value="2">Teaching staff</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="filterDepartments">Department</label>
                        <select name="filterDepartments" id="filterDepartments" class="form-control select2">
                            <option value="">All departments</option>
                            @foreach ($departments as $dRow)
                                <option value="{{ $dRow->id }}">{{ $dRow->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="filterStatus">Status</label>
                        <select name="filterStatus" id="filterStatus" class="form-control select2">
                            <option value="">All statuses</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 filter-toolbar-btn-wrap">
                        <button type="button" id="filter-button" onclick="dataCall()"
                            class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply filters</button>
                    </div>
                </div>
            </form>

            <div class="row app-table-sticky-wrap">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="hr-table-wrap hr-employees-table-wrap">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover data-table hr-data-table" id="ExportEmployeesList">
                                    <thead>
                                        {{ CommonHelper::displayPDFTableHeader('1000', 'View Employee List') }}
                                        <tr>
                                            <th class="text-center sticky-column sticky-column-header">Emp No / Name</th>
                                            <th class="text-center">Image</th>
                                            <th class="text-center">Father Name</th>
                                            <th class="text-center">Date of Birth</th>
                                            <th class="text-center">CNIC No</th>
                                            <th class="text-center">Address</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Phone No</th>
                                            <th class="text-center">Marital Status</th>
                                            <th class="text-center">Job Type</th>
                                            <th class="text-center">Employment Status</th>
                                            <th class="text-center">No. of Children</th>
                                            <th class="text-center">Reference Name</th>
                                            <th class="text-center">Reference Contact No</th>
                                            <th class="text-center">Reference Address</th>
                                            <th class="text-center">Status</th>
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
@endsection
@section('script')
    <script>
        function dataCall() {
            var baseUrl = '{{ url('/') }}';
            var columnTable = [{
                    data: null,
                    title: 'Emp No. / Name',
                    render: function(data) {
                        return `${data.emp_no} / ${data.emp_name}`;
                    },
                    className: 'sticky-column'
                },
                {
                    data: 'emp_image',
                    title: 'Image',
                    render: function(data) {
                        if (data) {
                            return `<img src="${data}" alt="" class="hr-employees-table-avatar" />`;
                        }
                        return '<img src="assets/img/no_image.png" alt="" class="hr-employees-table-avatar hr-employees-table-avatar--placeholder" />';
                    }
                },
                {
                    data: 'emp_father_name',
                    title: 'Father Name'
                },
                {
                    data: 'date_of_birth',
                    title: 'Date of Birth'
                },
                {
                    data: 'cnic_no',
                    title: 'CNIC No'
                },
                {
                    data: 'address',
                    title: 'Address'
                },
                {
                    data: 'emp_email',
                    title: 'Email'
                },
                {
                    data: 'phone_no',
                    title: 'Phone No'
                },
                {
                    data: 'maritarial_status',
                    title: 'Marital Status',
                    render: function(data) {
                        return data === 1 ? 'Married' : 'Unmarried';
                    }
                },
                {
                    data: 'job_type',
                    title: 'Job Type',
                    render: function(data) {
                        return data === 1 ? 'Full Time' : 'Part Time';
                    }
                },
                {
                    data: 'employment_status',
                    title: 'Employment Status',
                    render: function(data) {
                        return data === 1 ? 'Permanent' : 'Contract Base';
                    }
                },
                {
                    data: 'no_of_childern',
                    title: 'Number of Children'
                },
                {
                    data: 'relative_name',
                    title: 'Reference Name'
                },
                {
                    data: 'relative_contact_no',
                    title: 'Reference Contact No'
                },
                {
                    data: 'relative_address',
                    title: 'Reference Address'
                },
                {
                    data: 'status',
                    title: 'Status'
                },
                {
                    data: 'action',
                    title: 'Action',
                    class: 'hidden-print'
                }
            ];
            get_ajax_data_two('ExportEmployeesList', columnTable);
        }
        $(document).ready(function() {
            dataCall();
        });
    </script>
@endsection
