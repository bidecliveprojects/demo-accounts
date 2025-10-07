@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
<style>
    .table-responsive {
        overflow-x: auto;
        position: relative;
    }

    .table th,
    .table td {
        padding: 8px 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f2f2f2;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .sticky-column {
        position: sticky;
        left: 0;
        background-color: white;
        z-index: 5;
        /* Lower than header to avoid overlap */
    }

    .sticky-column-header {
        position: sticky;
        left: 0;
        background-color: #f2f2f2;
        /* Match header background */
        z-index: 10;
    }
</style>
@section('content')
    <div class="well_N">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeesList', '', '1'); ?>
                <button id="csv" onclick="generateCSVFile('ExportEmployeesList','View Employee List')"
                    class="btn btn-sm btn-warning">TO CSV</button>
                <button id="pdf" onclick="generatePDFFile('ExportEmployeesList','View Employee List')"
                    class="btn btn-sm btn-success">TO PDF</button>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="boking-wrp dp_sdw" id="PrintEmployeesList">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-print">
                    {{ CommonHelper::displayPageTitle('View Employee List') }}
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print">
                    <a href="{{ route('employees.create') }}" class="btn btn-success btn-xs">+ Create New</a>
                </div>
            </div>
            <form id="list_data" method="get" action="{{ route('employees.index') }}">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Employee Type</label>
                        <select name="filterEmpType" id="filterEmpType" class="form-control select2">
                            <option value="">All Employee Type</option>
                            <option value="1">Non Teaching Staff</option>
                            <option value="2">Teaching Staff</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Departments</label>
                        <select name="filterDepartments" id="filterDepartments" class="form-control select2">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dRow)
                                <option value="{{ $dRow->id }}">{{ $dRow->department_name }}</option>
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
                        <input type="button" id="filter-button" value="Filter" onclick="dataCall()"
                            class="btn btn-xs btn-success" />
                    </div>
                </div>
            </form>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive wrapper">
                                <table class="table table-responsive table-bordered data-table" id="ExportEmployeesList">
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
                                            <th class="text-center">No of Childern</th>
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
                        return `${data.emp_no} / ${data.emp_name}`; // Concatenate emp_no and emp_name
                    },
                    className: 'sticky-column' // Make this column sticky
                },
                {
                    data: 'emp_image',
                    title: 'Image',
                    render: function(data) {
                        if (data) {
                            return `<img src="${data}" alt="Image" style="width:50px; height:50px;" />`;
                        } else {
                            return '<img src="assets/img/no_image.png" alt="Default Image" style="width:50px; height:50px;" />';
                        }
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
                        return data === 1 ? 'Married' : 'Unmaried'; // Example of converting status code to text
                    }
                },
                {
                    data: 'job_type',
                    title: 'Job Type',
                    render: function(data) {
                        return data === 1 ? 'Full Time' : 'Part Time'; // Example of converting status code to text
                    }
                },
                {
                    data: 'employment_status',
                    title: 'Employment Status',
                    render: function(data) {
                        return data === 1 ? 'Permanent' :
                        'Contract Base'; // Example of converting status code to text
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
        dataCall();
        
    </script>
@endsection
