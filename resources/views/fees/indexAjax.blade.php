@php
    use App\Helpers\CommonHelper;
    $counter = 1;
    $totalAmount = 0;
@endphp

@foreach($feesList as $flRow)
    @php
        $totalAmount += $flRow->amount;
    @endphp
    <tr>
        <td class="text-center">{{$counter++}}</td>
        <td>{{$flRow->registration_no}}</td>
        <td>{{$flRow->student_name}}</td>
        <td>{{$flRow->father_name}}</td>
        <td>{{CommonHelper::changeDateformat($flRow->month_year)}}</td>
        <td class="text-right">{{number_format($flRow->amount,0)}}</td>
        <td class="text-center hidden-print">
            <div class="dropdown">
                <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a onclick="showDetailModelOneParamerter('fees/show','<?php echo $flRow->id;?>','View Fee Detail')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
<tr>
    <th colspan="5" class="text-center">Total</th>
    <th class="text-right">{{number_format($totalAmount)}}</th>
    <th class="text-center hidden-print">---</th>
</tr>