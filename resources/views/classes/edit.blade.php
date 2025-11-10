@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
	<div class="boking-wrp dp_sdw">
	    <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                {{CommonHelper::displayPageTitle('Edit Class Detail')}}
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <a href="{{ route('classes.index') }}" class="btn btn-success btn-xs">+ View List</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="{{ route('classes.update',$class->id) }}">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Class No</label>
                            <input type="text" name="class_no"
                            class="form-control @error('class_no') border border-danger @enderror"
                            id="class_no" value="{{$class->class_no}}" />
                            @error('class_no')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Class Name</label>
                            <input type="text" name="class_name"
                            class="form-control @error('class_name') border border-danger @enderror"
                            id="class_name" value="{{$class->class_name}}" />
                            @error('class_name')
                                <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fee Amount</label>
                            <input type="text" name="fee_amount"
                            class="form-control @error('fee_amount') border border-danger @enderror"
                            id="fee_amount" value="{{$class->fee_amount}}" />
                            @error('fee_amount')
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
@endsection
