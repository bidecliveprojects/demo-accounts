@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
	<div class="boking-wrp dp_sdw hr-page-card">
	    <div class="row hr-page-head">
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('Import Employee Attendance') }}
                <p class="hr-page-lead text-muted hidden-xs">Upload a spreadsheet file. Download the sample first if you need the correct format.</p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-right hr-page-actions">
                <a href="{{ route('attendances.index') }}" class="btn btn-default btn-sm"><i class="fa fa-list" aria-hidden="true"></i> View attendance list</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="{{ route('attendances.store') }}" enctype="multipart/form-data" class="hr-import-form">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="ImportFile">File to import <span class="text-danger">*</span></label>
                                {!! Form::file('sample_file', array('class' => 'form-control','id'=>'ImportFile')) !!}
                                {!! $errors->first('sample_file', '<p class="alert alert-danger">:message</p>') !!}
                                <span class="text-success"><?php if($errors->first() == '1'){echo 'Your File Import Successfully';}?></span>
                                <span class="text-danger"><?php if($errors->first() == '2'){echo 'Please Select File To Import';}?></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 hr-import-sample-wrap">
                            <label class="hidden-xs">&nbsp;</label>
                            <button type="button" class="btn btn-default btn-sm btn-block" onclick='window.location.href = "{{ URL::asset("/assets/import-sample-files/sample-employee-attendance.xlsx") }}"'>
                                <i class="fa fa-download" aria-hidden="true"></i> Download sample file
                            </button>
                        </div>
                    </div>
                    <div class="hr-form-actions row">
                        <div class="col-xs-12 text-right">
                            <button type="reset" id="reset" class="btn btn-default btn-sm">Clear</button>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check" aria-hidden="true"></i> Submit import</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
