@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('custom-css-end')
<link href="{{ URL::asset('assets/custom/css/multi-select-css-library.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="well_N employee-form-page hr-employees-module hr-employees-form">
	<div class="boking-wrp dp_sdw hr-employees-form-panel hr-page-card">
	    <div class="row hr-employees-form-head">
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                {{ CommonHelper::displayPageTitle('Add New Employee') }}
                <p class="hr-employees-form-lead text-muted">Complete each section. Fields marked <span class="text-danger">*</span> are required.</p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-right employee-form-toolbar hr-employees-back-toolbar">
                <a href="{{ route('employees.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to list</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" id="employee_create_form">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 employee-section-heading"><span><i class="fa fa-user"></i> Personal &amp; contact</span></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Employee Image</label>
                            <input type="file" name="emp_image"
                            class="form-control @error('emp_image') border border-danger @enderror"
                            id="emp_image" value="{{old('emp_image')}}" />
                            @error('emp_image')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Employee Type <span class="text-danger">*</span></label>
                            <select name="emp_type" id="emp_type" class="form-control select2">
                                <option value="1" {{ (string) old('emp_type', '1') === '1' ? 'selected' : '' }}>Non Teaching Staff</option>
                                <option value="2" {{ (string) old('emp_type', '1') === '2' ? 'selected' : '' }}>Teaching Staff</option>
                            </select>
                            @error('emp_type')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Employee Name</label>
                            <input type="text" name="emp_name"
                            class="form-control @error('emp_name') border border-danger @enderror"
                            id="emp_name" value="{{old('emp_name')}}" />
                            @error('emp_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Employee Father Name / Husband Name</label>
                            <input type="text" name="emp_father_name"
                            class="form-control @error('emp_father_name') border border-danger @enderror"
                            id="emp_father_name" value="{{old('emp_father_name')}}" />
                            @error('emp_father_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth"
                            class="form-control @error('date_of_birth') border border-danger @enderror"
                            id="date_of_birth" value="{{old('date_of_birth') ?? date('Y-m-d')}}" />
                            @error('date_of_birth')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>CNIC No</label>
                            <input type="text" name="cnic_no"
                            class="form-control @error('cnic_no') border border-danger @enderror"
                            id="cnic_no" value="{{old('cnic_no')}}" />
                            @error('cnic_no')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label for="emp_email">Employee Email <span id="emp_email_required_hint" class="text-danger" title="Required when Login Access is Yes" style="{{ (string) old('login_access', '1') === '2' ? '' : 'display: none;' }}">*</span></label>
                            <input type="email" name="emp_email" inputmode="email" autocomplete="email"
                            class="form-control @error('emp_email') border border-danger @enderror"
                            id="emp_email" value="{{old('emp_email')}}" />
                            <div id="emp_email_client_error" class="text-sm text-danger text-red-600" style="display: none;"></div>
                            @error('emp_email')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Employee Phone No</label>
                            <input type="text" name="phone_no"
                            class="form-control @error('phone_no') border border-danger @enderror"
                            id="phone_no" value="{{old('phone_no')}}" />
                            @error('phone_no')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Employee Address</label>
                            <textarea name="address"
                            class="form-control @error('address') border border-danger @enderror"
                            id="address">{{old('address')}}</textarea>
                            @error('address')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xs-12 employee-section-heading"><span><i class="fa fa-briefcase"></i> Employment &amp; documents</span></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>CNIC Document</label>
                            <input type="file" name="cnic_document[]" multiple id="cnic_document" class="form-control" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Other Document</label>
                            <input type="file" name="other_document[]" multiple id="other_document" class="form-control" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Job Type</label>
                            <select name="job_type" id="job_type" class="form-control select2">
                                <option value="1" {{ old('job_type') == '1' ? 'selected' : '' }}>Full Time</option>
                                <option value="2" {{ old('job_type') == '2' ? 'selected' : '' }}>Part Time</option>
                            </select>

                            @error('job_type')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Employment Status</label>
                            <select name="employment_status" id="employment_status" class="form-control select2">
                                <option value="1" {{ old('employment_status') == '1' ? 'selected' : '' }}>Permanent</option>
                                <option value="2" {{ old('employment_status') == '2' ? 'selected' : '' }}>Contract Base</option>
                            </select>

                            @error('employment_status')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Departments</label>
                            <select name="department_id" id="department_id" class="form-control select2">
                                @foreach($departments as $dRow)
                                    <option value="{{ $dRow->id }}" {{ old('department_id') == $dRow->id ? 'selected' : '' }}>
                                        {{ $dRow->department_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('department_id')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>City</label>
                            <select name="city_id" id="city_id" class="form-control select2">
                                @foreach($cities as $cRow)
                                    <option value="{{ $cRow->id }}" {{ old('city_id') == $cRow->id ? 'selected' : '' }}>
                                        {{ $cRow->city_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('city_id')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Date of Joining <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_joining"
                            class="form-control @error('date_of_joining') border border-danger @enderror"
                            id="date_of_joining" value="{{old('date_of_joining') ?? date('Y-m-d')}}" />
                            @error('date_of_joining')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xs-12 employee-section-heading"><span><i class="fa fa-address-book"></i> Reference &amp; guardian</span></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Reference Name</label>
                            <input type="text" name="relative_name"
                            class="form-control @error('relative_name') border border-danger @enderror"
                            id="relative_name" value="{{old('relative_name')}}" />
                            @error('relative_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Reference Contact No</label>
                            <input type="text" name="relative_contact_no"
                            class="form-control @error('relative_contact_no') border border-danger @enderror"
                            id="relative_contact_no" value="{{old('relative_contact_no')}}" />
                            @error('relative_contact_no')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Reference Address</label>
                            <input type="text" name="relative_address"
                            class="form-control @error('relative_address') border border-danger @enderror"
                            id="relative_address" value="{{old('relative_address')}}" />
                            @error('relative_address')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Guardian Name <span class="text-muted">(optional)</span></label>
                            <input type="text" name="guardian_name"
                            class="form-control @error('guardian_name') border border-danger @enderror"
                            id="guardian_name" value="{{old('guardian_name')}}" />
                            @error('guardian_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Guardian Contact No <span class="text-muted">(optional)</span></label>
                            <input type="text" name="guardian_mobile_no"
                            class="form-control @error('guardian_mobile_no') border border-danger @enderror"
                            id="guardian_mobile_no" value="{{old('guardian_mobile_no')}}" placeholder="e.g. 92300-0000000" />
                            @error('guardian_mobile_no')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Guardian Address <span class="text-muted">(optional)</span></label>
                            <input type="text" name="guardian_address"
                            class="form-control @error('guardian_address') border border-danger @enderror"
                            id="guardian_address" value="{{old('guardian_address')}}" />
                            @error('guardian_address')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Marital Status</label>
                            <select name="maritarial_status" id="maritarial_status" class="form-control select2">
                                <option value="1" {{ (string) old('maritarial_status', '1') === '1' ? 'selected' : '' }}>Married</option>
                                <option value="2" {{ (string) old('maritarial_status', '1') === '2' ? 'selected' : '' }}>Unmarried</option>
                            </select>
                            @error('maritarial_status')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>No of Children</label>
                            <select name="no_of_childern" id="no_of_childern" class="form-control select2">
                                @for ($a = 0; $a <= 20; $a++)
                                    <option value="{{ $a }}" {{ old('no_of_childern') == $a ? 'selected' : '' }}>
                                        {{ $a }}
                                    </option>
                                @endfor
                            </select>

                            @error('no_of_childern')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Login Access</label>
                            <select name="login_access" id="login_access" class="form-control select2">
                                <option value="1" {{ old('login_access') == '1' ? 'selected' : '' }}>No</option>
                                <option value="2" {{ old('login_access') == '2' ? 'selected' : '' }}>Yes</option>
                            </select>

                            @error('login_access')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label for="roles">Assign roles</label>
                                <select name="roles[]" id="roles" class="form-control select2" multiple>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('roles')
                                    <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 employee-section-heading"><span><i class="fa fa-clock-o"></i> Access &amp; attendance</span></div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Grace Time</label>
                            <select name="grace_time" id="grace_time" class="form-control select2">
                                @for ($a = 1; $a <= 30; $a++)
                                    <option value="{{ $a }}" {{ old('grace_time') == $a ? 'selected' : '' }}>
                                        {{ $a }}
                                    </option>
                                @endfor
                            </select>

                            @error('grace_time')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Start Time</label>
                            <input type="text" name="start_time"
                            class="form-control @error('start_time') border border-danger @enderror"
                            id="start_time" value="{{old('start_time')}}" />
                            @error('start_time')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>End Time</label>
                            <input type="text" name="end_time"
                            class="form-control @error('end_time') border border-danger @enderror"
                            id="end_time" value="{{old('end_time')}}" />
                            @error('end_time')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div id="item_list">
                        <div class="row hr-employees-exp-row">
                            <div class="col-xs-12 employee-section-heading"><span><i class="fa fa-building"></i> Work experience</span></div>
                            <input type="hidden" name="experienceArray[]" id="experienceArray" value="1" />
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label>Organization Name</label>
                                <input type="text" name="organization_name_1" id="organization_name_1" value="" class="form-control" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label>Reason of Resign</label>
                                <input type="text" name="reason_of_resign_1" id="reason_of_resign_1" value="" class="form-control" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <label>Duration</label>
                                <input type="text" name="duration_1" id="duration_1" value="" class="form-control" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 employee-exp-actions">
                                <button type="button" class="btn btn-primary btn-sm add_item_btn employee-exp-add-btn">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add row
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="singleCampusPayrollDetail">
                        <div class="row">
                            <div class="col-xs-12 employee-section-heading"><span><i class="fa fa-money"></i> Salary</span></div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Salary</label>
                                <input type="number" name="basic_salary" id="basic_salary" 
                                    value="{{ old('basic_salary') }}" class="form-control" />

                                @error('basic_salary')
                                    <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-right employee-form-toolbar form-actions hr-employees-form-actions">
                            <button type="reset" id="reset" class="btn btn-default"><i class="fa fa-undo" aria-hidden="true"></i> Clear</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save employee</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{ URL::asset('assets/custom/js/multi-select-js-library.js') }}"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
    <script>
        function sallarySectionShowHide(){
            var multipleSchoolCampus = $('#multiple_school_campus').val();
            if(multipleSchoolCampus == 2){
                $('#multipleCampusPayrollDetail').show();
                $('#singleCampusPayrollDetail').hide();
                $('#dates-field2').multiselect('enable');
            }else{
                $('#singleCampusPayrollDetail').show();
                $('#multipleCampusPayrollDetail').hide();
                $('#dates-field2').multiselect('disable');
                
            }
        }
        sallarySectionShowHide();
        function loadSchoolCampusDetailDependCampusIds() {
            // Get selected values from the multiselect
            const selectedValues = $('#dates-field2').val();

            // Check if there are selected values
            if (!selectedValues || selectedValues.length === 0) {
                $('#multipleCampusPayrollDetail').html('');
                return;
            }

            // Make the AJAX request
            $.ajax({
                url: '{{ url("/loadSchoolCampusDetailDependCampusIds") }}',
                method: 'GET',
                data: { company_location_ids: selectedValues },
                success: function(response) {
                    $('#multipleCampusPayrollDetail').html(response);
                    // Handle success response
                    //alert(response);
                },
                error: function(xhr, status, error) {
                    // Log error details
                    console.error('AJAX Error:', { status, error });
                }
            });
        }
        $('.select2').select2();

        function syncEmailRequiredForLoginAccess() {
            var loginYes = $('#login_access').val() === '2';
            var $hint = $('#emp_email_required_hint');
            var $email = $('#emp_email');
            var $err = $('#emp_email_client_error');
            if (loginYes) {
                $hint.show();
                $email.prop('required', true).attr('aria-required', 'true');
            } else {
                $hint.hide();
                $email.prop('required', false).removeAttr('aria-required');
                $err.hide().text('');
                $email.removeClass('border-danger');
            }
        }

        $('#login_access').on('change select2:select', syncEmailRequiredForLoginAccess);
        syncEmailRequiredForLoginAccess();

        $('#employee_create_form').on('submit', function (e) {
            if ($('#login_access').val() !== '2') {
                return;
            }
            var raw = ($('#emp_email').val() || '').trim();
            var $err = $('#emp_email_client_error');
            $('#emp_email').removeClass('border-danger');
            $err.hide().text('');
            if (!raw) {
                e.preventDefault();
                $('#emp_email').addClass('border-danger').prop('required', true);
                $err.text('Email is required when Login Access is Yes.').show();
                $('#emp_email').trigger('focus');
                return false;
            }
            var basicEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!basicEmail.test(raw)) {
                e.preventDefault();
                $('#emp_email').addClass('border-danger');
                $err.text('Please enter a valid email address.').show();
                $('#emp_email').trigger('focus');
                return false;
            }
        });

        // Add Item
        var counter = 1;
        $('.add_item_btn').click(function() {
            ++counter;
            $('#item_list').append(`
                <div class="row hr-employees-exp-row">
                    <input type="hidden" name="experienceArray[]" id="experienceArray" value="`+counter+`" />
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label>Organization Name</label>
                        <input type="text" name="organization_name_`+counter+`" id="organization_name_`+counter+`" value="" class="form-control" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label>Reason of Resign</label>
                        <input type="text" name="reason_of_resign_`+counter+`" id="reason_of_resign_`+counter+`" value="" class="form-control" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label>Duration</label>
                        <input type="text" name="duration_`+counter+`" id="duration_`+counter+`" value="" class="form-control" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 employee-exp-actions">
                        <button type="button" class="btn btn-danger btn-sm remove_item_btn employee-exp-remove-btn">
                            <i class="fa fa-trash" aria-hidden="true"></i> Remove
                        </button>
                    </div>
                </div>
            `);
        });

        // Remove Item
        $(document).on('click', '.remove_item_btn', function() {
            $(this).parent().parent().remove();
        });
        $(document).ready(function(){
            $('#cnic_no').mask('00000-0000000-0');
            $('#phone_no').mask('92300-0000000');
            $('#relative_contact_no').mask('92300-0000000');
        });
        $(function() {
            $('.multiselect-ui').multiselect({
                includeSelectAllOption: false
            });
        });
    </script>
@endsection
