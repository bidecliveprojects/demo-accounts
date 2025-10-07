@php
    use App\Helpers\CommonHelper;
@endphp
@foreach($completedParasList as $dRow)
    @php
        if($dRow->countDays <= $dRow->excelent)
            $rowColor = 'success';
        elseif($dRow->countDays <= $dRow->good)
            $rowColor = 'warning';
        else
            $rowColor = 'danger';
    @endphp
    <tr class="{{$rowColor}}">
        
        <td class="text-center">{{ $loop->index + 1 }}</td>

        <td>{{ $dRow->student_name }}</td>
        <td>{{ $dRow->para_name }}</td>
        <td class="text-center">{{ $dRow->total_lines_in_para }}</td>
        <td class="text-center">{{ $dRow->excelent }}</td>
        <td class="text-center">{{ $dRow->good }}</td>
        <td class="text-center">{{ $dRow->average }}</td>
        <td class="text-center">{{ $dRow->estimated_completion_days }}</td>

        <td class="text-center">{{ $dRow->countDays }}</td>
        <td class="text-center">{{ $dRow->countHolidays }}</td>
        <td class="text-center">{{ $dRow->countLeaves }}</td>
        <td class="text-center">{{ $dRow->countDays + $dRow->countHolidays + $dRow->countLeaves }}</td>
        <td class="text-center hidden-print">
            <div class="dropdown">
                <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a onclick="showDetailModelOneParamerter('studentperformances/show','<?php echo $dRow->student_id.'<*>'.$dRow->paraId;?>','View Student Performance Para Wise Detail')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
