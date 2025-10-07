@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')
@section('content')
<div class="well_N">
    <div class="boking-wrp dp_sdw">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                {{CommonHelper::displayPageTitle('Generate Fee Voucher')}}
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right hidden-print">
                <a href="{{ route('fees.generate-fee-voucher-list') }}" class="btn btn-success btn-xs">+ View List</a>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <form method="POST" action="{{ route('fees.generate-fee-voucher-store') }}">
            @csrf
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Class</label>
                    <select name="class_id" id="class_id" class="form-control">
                        @foreach (CommonHelper::get_all_classes(1) as $row)
                            <option value="{{ $row->id }}">{{ $row->class_name }}</option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Month-Year</label>
                    <input type="month" name="month_year" id="month_year" class="form-control" />
                </div>
                <div class="col-sm-6 col-sm-offset-3 lineHeight">&nbsp;</div>
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Description</label>
                    <input type="text" name="description" id="description" class="form-control" />
                </div>
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Debit Account</label>
                    <select name="debit_acc_id" id="debit_acc_id" class="form-control select2">
                        @foreach ($chartOfAccountList as $row)
                            <option value="{{ $row->acc_id }}">{{ $row->name }}</option>
                        @endforeach
                    </select>
                    @error('debit_acc_id')
                        <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 col-sm-offset-3">
                    <label>Credit Account</label>
                    <select name="credit_acc_id" id="credit_acc_id" class="form-control select2">
                        @foreach ($chartOfAccountList as $row)
                            <option value="{{ $row->acc_id }}">{{ $row->name }}</option>
                        @endforeach
                    </select>
                    @error('credit_acc_id')
                        <div class="text-sm text-danger text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 col-sm-offset-3 lineHeight">&nbsp;</div>
                <div class="col-sm-6 col-sm-offset-3 text-right">
                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection