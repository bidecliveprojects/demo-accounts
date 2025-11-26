@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<div class="well_N">
	<div class="boking-wrp dp_sdw">
	    <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                {{CommonHelper::displayPageTitle('Edit Student Detail')}}
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <a href="{{ route('students.index') }}" class="btn btn-success btn-xs">+ View List</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="{{ route('students.update',$student->id) }}" enctype="multipart/form-data">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeading">
                            Student Information
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidden">
                            <label>Student Section <span class="text-danger">*</span></label>
                            <select name="student_section_type" id="student_section_type" class="form-control" onchange="generateRegistrationNo()">
                                <option value="1" @if($student->student_section_type == 1) selected @endif>Maktab</option>
                                <option value="2" @if($student->student_section_type == 2) selected @endif>Tehfeez</option>
                            </select>
                            <input type="hidden" name="old_student_section_type" id="old_student_section_type" value="{{$student->student_section_type}}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Registration No</label>
                            <input type="text" name="registration_no" @if(Session::get('registration_no_option') == 1) readonly @endif id="registration_no" value="{{$student->registration_no}}" class="form-control" />
                            <input type="hidden" name="registration_no_option" id="registration_no_option" value="{{ Session::get('registration_no_option') }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Date of Admission <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_admission"
                            class="form-control @error('date_of_admission') border border-danger @enderror"
                            id="date_of_admission" value="{{$student->date_of_admission ?? date('Y-m-d')}}" />
                            @error('date_of_admission')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Student Name <span class="text-danger">*</span></label>
                            <input type="text" name="student_name"
                            class="form-control @error('student_name') border border-danger @enderror"
                            id="student_name" value="{{$student->student_name}}" />
                            @error('student_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth"
                            class="form-control @error('date_of_birth') border border-danger @enderror"
                            id="date_of_birth" value="{{$student->date_of_birth ?? date('Y-m-d')}}" />
                            @error('date_of_birth')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden">
                            <label>Previous Madrasa</label>
                            <input type="text" name="previous_madrasa"
                            class="form-control @error('previous_madrasa') border border-danger @enderror"
                            id="previous_madrasa" value="{{$student->previous_madrasa}}" />
                            @error('previous_madrasa')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Class</label>
                            <select name="class_id" id="class_id" class="form-control">
                                @foreach (CommonHelper::get_all_classes(1) as $row)
                                    <option value="{{ $row->id }}" @if($student->class_id == $row->id) selected @endif>{{ $row->class_name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden">
                            <label>Grade Class Applied For <span class="text-danger">*</span></label>
                            <input type="text" name="grade_class_applied_for"
                            class="form-control @error('grade_class_applied_for') border border-danger @enderror"
                            id="grade_class_applied_for" value="{{$student->grade_class_applied_for}}" />
                            @error('grade_class_applied_for')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Reference</label>
                            <input type="text" name="reference"
                            class="form-control @error('reference') border border-danger @enderror"
                            id="reference" value="{{$student->reference}}" />
                            @error('reference')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeading">
                            PARENT AND GUARDIAN INFORMATION
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Father Name <span class="text-danger">*</span></label>
                            <input type="text" name="father_name"
                            class="form-control @error('father_name') border border-danger @enderror"
                            id="father_name" value="{{$student->student_guardian_information->father_name}}" />
                            @error('father_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Mother Name <span class="text-danger">*</span></label>
                            <input type="text" name="mother_name"
                            class="form-control @error('mother_name') border border-danger @enderror"
                            id="mother_name" value="{{$student->student_guardian_information->mother_name}}" />
                            @error('mother_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Father Qualification <span class="text-danger">*</span></label>
                            <input type="text" name="father_qualification"
                            class="form-control @error('father_qualification') border border-danger @enderror"
                            id="father_qualification" value="{{$student->student_guardian_information->father_qualification}}" />
                            @error('father_qualification')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Mother Qualification</label>
                            <input type="text" name="mother_qualification"
                            class="form-control @error('mother_qualification') border border-danger @enderror"
                            id="mother_qualification" value="{{$student->student_guardian_information->mother_qualification}}" />
                            @error('mother_qualification')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>City</label>
                            <select name="city_id" id="city_id" class="form-control">
                                @foreach (CommonHelper::get_all_cities(1) as $row)
                                    <option value="{{ $row->id }}" @if($student->student_guardian_information->city_id == $row->id) selected @endif>{{ $row->city_name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Cnic No <span class="text-danger">*</span></label>
                            <input type="text" name="cnic_no"
                            class="form-control @error('cnic_no') border border-danger @enderror"
                            id="cnic_no" value="{{$student->student_guardian_information->cnic_no}}" />
                            @error('cnic_no')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no"
                            class="form-control @error('mobile_no') border border-danger @enderror"
                            id="mobile_no" value="{{$student->student_guardian_information->mobile_no}}" />
                            @error('mobile_no')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Parent Email</label>
                            <input type="text" name="parent_email"
                            class="form-control @error('parent_email') border border-danger @enderror"
                            id="parent_email" value="{{$student->student_guardian_information->parent_email}}" />
                            @error('parent_email')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Father Occupation <span class="text-danger">*</span></label>
                            <input type="text" name="father_occupation"
                            class="form-control @error('father_occupation') border border-danger @enderror"
                            id="father_occupation" value="{{$student->student_guardian_information->father_occupation}}" />
                            @error('father_occupation')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Mother Tongue <span class="text-danger">*</span></label>
                            <input type="text" name="mother_tongue"
                            class="form-control @error('mother_tongue') border border-danger @enderror"
                            id="mother_tongue" value="{{$student->student_guardian_information->mother_tongue}}" />
                            @error('mother_tongue')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Home Address <span class="text-danger">*</span></label>
                            <input type="text" name="home_address"
                            class="form-control @error('home_address') border border-danger @enderror"
                            id="home_address" value="{{$student->student_guardian_information->home_address}}" />
                            @error('home_address')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Specify Any Health Problem Medication</label>
                            <input type="text" name="specify_any_health_problem_medication"
                            class="form-control @error('specify_any_health_problem_medication') border border-danger @enderror"
                            id="specify_any_health_problem_medication" value="{{$student->student_guardian_information->specify_any_health_problem_medication}}" />
                            @error('specify_any_health_problem_medication')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Concession Fees <span class="text-danger">*</span></label>
                            <input type="number" name="concession_fees"
                            class="form-control @error('concession_fees') border border-danger @enderror"
                            id="concession_fees" value="{{$student->concession_fees ?? 0}}" />
                            @error('concession_fees')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeading hidden">
                            DOCUMENT REQUIRED WITH REGISTRATION FORM
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Birth Certificate <span class="text-danger">*</span></label>
                            <input type="file" name="birth_certificate"
                            class="form-control @error('birth_certificate') border border-danger @enderror"
                            id="birth_certificate" value="{{old('birth_certificate')}}" />
                            @error('birth_certificate')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Father Guardian Cnic <span class="text-danger">*</span></label>
                            <input type="file" name="father_guardian_cnic"
                            class="form-control @error('father_guardian_cnic') border border-danger @enderror"
                            id="father_guardian_cnic" value="{{old('father_guardian_cnic')}}" />
                            @error('father_guardian_cnic')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Father Guardian Cnic Back <span class="text-danger">*</span></label>
                            <input type="file" name="father_guardian_cnic_back"
                            class="form-control @error('father_guardian_cnic_back') border border-danger @enderror"
                            id="father_guardian_cnic_back" value="{{old('father_guardian_cnic_back')}}" />
                            @error('father_guardian_cnic_back')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Passport Size Photo <span class="text-danger">*</span></label>
                            <input type="file" name="passport_size_photo"
                            class="form-control @error('passport_size_photo') border border-danger @enderror"
                            id="passport_size_photo" value="{{old('passport_size_photo')}}" />
                            @error('passport_size_photo')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Copy Of Last Report</label>
                            <input type="file" name="copy_of_last_report"
                            class="form-control @error('copy_of_last_report') border border-danger @enderror"
                            id="copy_of_last_report" value="{{old('copy_of_last_report')}}" />
                            @error('copy_of_last_report')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}
                    <div class="row hidden">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeading">
                            MADARSA TIMING AND FEES
                        </div>
                    </div>
                    <div class="row hidden">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Student Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-control">
                                @foreach (CommonHelper::get_all_departments(1) as $row)
                                    <option value="{{ $row->id }}" @if($student->department_id == $row->id) selected @endif>{{ $row->department_name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden">
                            <label>Class Timing <span class="text-danger">*</span></label>
                            <input type="text" name="class_timing"
                            class="form-control @error('class_timing') border border-danger @enderror"
                            id="class_timing" value="{{$student->class_timing}}" />
                            @error('class_timing')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden">
                            <label>Fees <span class="text-danger">*</span></label>
                            <input type="text" name="fees"
                            class="form-control @error('fees') border border-danger @enderror"
                            id="fees" value="{{$student->fees}}" />
                            @error('fees')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Application for Concession fees</label>
                            <input type="file" name="consession_fees_image"
                            class="form-control @error('consession_fees_image') border border-danger @enderror"
                            id="consession_fees_image" value="{{old('consession_fees_image')}}" />
                            @error('consession_fees_image')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Class Teacher Name <span class="text-danger">*</span></label>
                            <select name="teacher_id" id="teacher_id" class="form-control">
                                @foreach (CommonHelper::get_all_employees(2) as $row)
                                    <option value="{{ $row->id }}" @if($student->teacher_id == $row->id) selected @endif>{{ $row->emp_name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function generateRegistrationNo(){
        var studentSectionType = $('#student_section_type').val();

    }
    $(document).ready(function(){
        $('#cnic_no').mask('00000-0000000-0');
        $('#mobile_no').mask('92300-0000000');
    });
</script>
@endsection
