@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
	<div class="boking-wrp dp_sdw">
	    <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                {{CommonHelper::displayPageTitle('Add Import Employee Attendance')}}
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <a href="{{ route('attendances.index') }}" class="btn btn-success btn-xs"><span></span> View List</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="{{ route('attendances.store') }}" enctype="multipart/form-data">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @csrf
                    <div class="row">
                        <div class="col-xs-8 col-sm-8 col-md-8 col-sm-12">
                            <div class="form-group">
                                <label class="sf-label">Select File to Import:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <div class="">
                                    {!! Form::file('sample_file', array('class' => 'form-control','id'=>'ImportFile')) !!}
                                    {!! $errors->first('sample_file', '<p class="alert alert-danger">:message</p>') !!}
                                    <span class="text-success"><?php if($errors->first() == '1'){echo 'Your File Import Successfully';}?></span>
                                    <span class="text-danger"><?php if($errors->first() == '2'){echo 'Please Select File To Import';}?></span>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <input type="button" value="Download Sample File" onclick='window.location.href = "{{ URL::asset("/assets/import-sample-files/sample-employee-attendance.xlsx") }}"' style="margin: 30px;" class="btn btn-sm btn-success" />
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
@endsection
