@php
    $counter = 1;
    use App\Helpers\CommonHelper;
@endphp
@foreach($attendanceList as $alRow)
    <tr>
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center">{{$alRow->emp_no}}</td>
        <td>{{$alRow->emp_name}}</td>
        <td class="text-center">{{CommonHelper::changeDateformat($alRow->date)}}</td>
        <td class="text-center">{{$alRow->clock_in}}</td>
        <td class="text-center">{{$alRow->clock_out}}</td>
        <td class="text-center">{{CommonHelper::calculateTotalHours($alRow->clock_in,$alRow->clock_out)}}</td>
        <td></td>
        <td class="text-center">
            @if($alRow->clock_in == '00:00' || $alRow->clock_out == '00:00')
                Yes
            @endif
        </td>
    </tr>
@endforeach