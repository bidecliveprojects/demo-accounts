@php
    $counter = 1;
@endphp
@foreach($getData as $gdRow)
    <tr>
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{$gdRow->registration_no}}</td>
        <td>{{$gdRow->student_name}}</td>
        <td>{{$gdRow->father_name}}</td>
        <td class="text-center">{{$gdRow->mobile_no}}</td>
        <td class="text-center">{{$gdRow->month_year}}</td>
        <td>{{$gdRow->description}}</td>
        <td class="text-right">{{number_format($gdRow->amount,0)}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a onclick="showDetailModelOneParamerter('fees/viewGenerateFeeVoucherDetail','<?php echo $gdRow->id;?>','View Generate Fee Voucher Detail')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach