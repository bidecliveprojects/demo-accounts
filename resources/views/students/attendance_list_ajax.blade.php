@php
    use App\Helpers\CommonHelper;
@endphp
@foreach($attenlanceList as $dRow)
    <tr>
        <td class="text-center">{{ $loop->index + 1 }}</td>

        <td>{{ $dRow->registration_no }}</td>
        <td>{{ $dRow->student_name }}</td>
        <td>{{ $dRow->father_name }}</td>
        <td>{{ $dRow->mobile_no }}</td>
        <td>{{ $monthYear }}</td>

        <td class="text-center">{{ $dRow->total_days }}</td>
        <td class="text-center">{{ $dRow->present_days }}</td>
        <td class="text-center">{{ $dRow->leave_days }}</td>
        <td class="text-center">{{ $dRow->holidays_days }}</td>
        <td class="text-center hidden-print">
            <div class="dropdown">
                <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a onclick="showDetailModelOneParamerter('students/viewStudentAttendanceDetail','<?php echo $dRow->id.'<*>'.$monthYear;?>','View Student Attendance Detail')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
