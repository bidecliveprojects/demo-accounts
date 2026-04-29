@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw hr-page-card">
        <div class="row hr-employees-form-head">
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                {{ CommonHelper::displayPageTitle('Add Employee Loan') }}
                <p class="hr-employees-form-lead text-muted hidden-xs">Select an employee and enter loan terms. Required fields are marked <span class="text-danger">*</span>.</p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 text-right hr-employees-back-toolbar">
                <a href="{{ route('loan.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to list</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" action="{{ route('loan.store') }}">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="employee_id">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-control select2">
                                <option value="">Select employee</option>
                                @foreach($employeeList as $elRow)
                                    <option value="{{$elRow->id}}" {{ old('employee_id') == $elRow->id ? 'selected' : '' }}>
                                        {{$elRow->emp_name}}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="apply_loan_date">Loan apply date</label>
                            <input type="date" name="apply_loan_date" id="apply_loan_date" class="form-control" value="{{ old('apply_date') }}" />
                            @error('apply_loan_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="amount">Loan amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', 0) }}" class="form-control" step="any" />
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="per_month_deduction">Monthly deduction</label>
                            <input type="number" name="per_month_deduction" id="per_month_deduction" value="{{ old('per_month_deduction', 0) }}" class="form-control" step="any" />
                            @error('per_month_deduction')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', '-') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="hr-form-actions row">
                        <div class="col-xs-12 text-right">
                            <button type="reset" id="reset" class="btn btn-default btn-sm">Clear</button>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check" aria-hidden="true"></i> Save loan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
