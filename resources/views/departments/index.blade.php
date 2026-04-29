@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card" id="PrintDepartmentsList">
        <div class="row hr-page-head hidden-print">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('View Department List') }}
                <p class="hr-page-lead text-muted hidden-xs">Organizational departments for staff and HR.</p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right hr-page-actions hidden-print">
                {!! CommonHelper::displayPrintButtonInBlade('PrintDepartmentsList', '', '1') !!}
                <div class="btn-group hr-export-group" role="group" aria-label="Export">
                    <button type="button" id="csv" onclick="generateCSVFile('ExportDepartmentsList','View Department List')" class="btn btn-default btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> CSV</button>
                    <button type="button" id="pdf" onclick="generatePDFFile('ExportDepartmentsList','View Department List')" class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</button>
                </div>
                <a href="{{ route('departments.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New department</a>
            </div>
        </div>
        <form id="list_data" method="get" action="{{ route('departments.index') }}" class="hr-filter-form">
            <div class="row filter-toolbar-actions hr-filter-row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <label for="filterStatus">Status</label>
                    <select name="filterStatus" id="filterStatus" class="form-control select2">
                        <option value="">All statuses</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 hr-filter-submit-wrap">
                    <button type="button" onclick="get_ajax_data()" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i> Apply</button>
                </div>
            </div>
        </form>
        <div class="hr-table-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover hr-data-table" id="ExportDepartmentsList">
                    {{ CommonHelper::displayPDFTableHeader('1000','View Department List') }}
                    <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Department Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center hidden-print">Action</th>
                        </tr>
                    </thead>
                    <tbody id="data"></tbody>
                </table>
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

        $('body').on('click', '.delete-department-record', function (e) {
            e.preventDefault();
            var userURL = $(this).data('url');
            if (!confirm('Delete this department permanently? This cannot be undone.')) {
                return;
            }
            $.ajax({
                url: userURL,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function (data) {
                    if (typeof data.success === 'undefined') {
                        alert(data.catchError || 'Could not delete.');
                        return;
                    }
                    alert(data.success);
                    get_ajax_data();
                },
                error: function () {
                    alert('Could not delete.');
                }
            });
        });
    </script>
@endsection
