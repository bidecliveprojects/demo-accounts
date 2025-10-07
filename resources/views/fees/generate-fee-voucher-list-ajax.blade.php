@php
    use App\Helpers\CommonHelper;
    $counter = 1;
    $totalAmount = 0;
@endphp

@foreach($getGenerateFeeVoucherList as $ggfvlRow)
    @php
        $totalAmount += $ggfvlRow->totalVoucherAmount;
    @endphp
    <tr>
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{CommonHelper::changeDateformat($ggfvlRow->month_year)}}</td>
        <td>{{$ggfvlRow->description}}</td>
        <td class="text-right">{{number_format($ggfvlRow->totalVoucherAmount,0)}}</td>
        <td class="text-center hidden-print">
            <div class="dropdown">
                <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
                <ul class="dropdown-menu">
                </ul>
            </div>
        </td>
    </tr>
@endforeach
<tr>
    <th class="text-center" colspan="3">Total</th>
    <th class="text-right">{{number_format($totalAmount)}}</th>
    <th class="text-center hidden-print">---</th>
</tr>