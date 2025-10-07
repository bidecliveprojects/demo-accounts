@php
    $counter = 1;
@endphp


    @foreach($getData as $gdRow)
        <tr>
            <td class="text-center">{{$counter++}}</td>
            <td>{{$gdRow->registration_no}}</td>
            <td>{{$gdRow->student_name}}</td>
            <td>{{$gdRow->father_name}}</td>
            <td class="text-right">{{$gdRow->month_year}}</td>
            <td class="text-right">{{number_format($gdRow->amount,0)}}</td>
            <td class="text-right">{{$gdRow->description}}</td>
            <td>
                <input type="hidden" name="generate_fee_voucher_data_ids_array[]" id="generate_fee_voucher_data_ids_array" value="{{$gdRow->id}}" />
                <input type="hidden" name="jv_id_{{$gdRow->id}}" id="jv_id_{{$gdRow->id}}" value="{{$gdRow->jv_id}}" />
                <input type="hidden" name="receipt_voucher_s_id_{{$gdRow->id}}" id="receipt_voucher_s_id_{{$gdRow->id}}" value="{{$gdRow->student_id}}" />
                <input type="hidden" name="receipt_voucher_gfv_id_{{$gdRow->id}}" id="receipt_voucher_gfv_id_{{$gdRow->id}}" value="{{$gdRow->generate_fee_voucher_id}}" />
                <input type="hidden" name="amount_{{$gdRow->id}}" id="amount_{{$gdRow->id}}" value="{{$gdRow->amount}}" />
                <select name="fee_voucher_status_{{$gdRow->id}}" id="fee_voucher_status_{{$gdRow->id}}" class="form-control">
                    <option value="1" @if($gdRow->fee_voucher_status == 1) selected @endif>Unpaid</option>
                    <option value="2" @if($gdRow->fee_voucher_status == 2) selected @endif>Paid</option>
                </select>
            </td>
            <td>
                <select name="debit_acc_id_{{$gdRow->id}}" id="debit_acc_id_{{$gdRow->id}}" class="form-control select2">
                    @foreach ($chartOfAccountList as $row)
                        <option value="{{ $row->acc_id }}">{{ $row->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    @endforeach
    @if(count($getData) != 0)
        <tr>
            <td colspan="8" class="text-center">---</td>
            <td class="text-center"><button type="submit" class="btn btn-sm btn-success btnSubmit">Submit</button></td>
        </tr>
    @endif