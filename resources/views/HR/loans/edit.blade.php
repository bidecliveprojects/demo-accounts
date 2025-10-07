@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                {{ CommonHelper::displayPageTitle('Edit Employee Loan Detail') }}
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <a href="{{ route('loan.index') }}" class="btn btn-success btn-xs"><span></span> View List</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="{{ route('loan.update', $loan->id) }}">
                @csrf <!-- This is important for a PUT request -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Employee Detail</label>
                            <select name="employee_id" id="employee_id" class="form-control select2">
                                <option value="">Select Employee</option>
                                @foreach($employeeList as $elRow)
                                    <option value="{{$elRow->id}}" {{ old('employee_id', $loan->employee_id) == $elRow->id ? 'selected' : '' }}>
                                        {{$elRow->emp_name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Loan Apply Date</label>
                            <input type="date" name="apply_loan_date" id="apply_loan_date" class="form-control" value="{{ old('apply_loan_date', $loan->apply_loan_date) }}" />
                            @error('apply_loan_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Apply Loan Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $loan->amount) }}" class="form-control" />
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Loan Deduction Amount</label>
                            <input type="number" name="per_month_deduction" id="per_month_deduction" value="{{ old('per_month_deduction', $loan->per_month_deduction) }}" class="form-control" />
                            @error('per_month_deduction')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Description</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description', $loan->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            <button type="submit" class="btn btn-sm btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection