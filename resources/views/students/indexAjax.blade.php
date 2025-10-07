@php
    use App\Helpers\CommonHelper;
@endphp
@foreach($students as $dRow)
<tr class="@if($dRow->status == 1) success @else danger @endif">
    <td class="text-center sticky-col first-col">{{ $loop->index + 1 }}</td>
    <td class="text-center sticky-col second-col hidden-print">
        <div class="dropdown">
            <button class="btn btn-xs dropdown-toggle theme-btn" type="button" data-toggle="dropdown">Action  <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a onclick="showDetailModelOneParamerter('students/show','<?php echo $dRow->id;?>','View Student Detail')"><span class="glyphicon glyphicon-eye-open"></span> View</a></li>
                @if($dRow->status == 1)
                    <li><a href="{{ route('students.edit', $dRow->id) }}"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
                    <li><a id="inactive-record" data-url="{{ route('students.destroy', $dRow->id) }}">Inactive</a></li>
                    <li><a onclick="showDetailModelOneParamerter('students/updateCurrentParaForm','<?php echo $dRow->id;?>','Update Current Para Detail')"><span class="glyphicon glyphicon-eye-open"></span> Update Current Para</a></li>
                    <li><a href="{{ route('updateStudentDocumentForm', $dRow->id) }}"><span class="glyphicon glyphicon-eye-open"></span> Update Student Document</a></li>
                @else
                    <li><a id="active-record" data-url="{{ route('students.active', $dRow->id) }}">Active</a></li>
                @endif
            </ul>
        </div>
    </td>
    <td class="sticky-col third-col">{{ $dRow->registration_no }}</td>
    <td class="sticky-col fourth-col">{{ $dRow->student_name }}</td>
    <td class="sticky-col fifth-col">{{CommonHelper::display_document($dRow->passport_size_photo)}}</td>
    <td>{{ CommonHelper::changeDateformat($dRow->date_of_admission) }}</td>
    <td>{{ $dRow->father_name }}</td>
    <td>{{ $dRow->parent_email }}</td>
    <td>{{ $dRow->mobile_no }}</td>
    <td>{{ $dRow->cnic_no }}</td>
    <td>{{CommonHelper::display_document($dRow->birth_certificate)}}</td>
    <td>{{CommonHelper::display_document($dRow->father_guardian_cnic)}}</td>
    <td>{{CommonHelper::display_document($dRow->father_guardian_cnic_back)}}</td>
    <td>{{CommonHelper::display_document($dRow->copy_of_last_report)}}</td>

    <td>{{ $dRow->department_name }}</td>
    <td>{{ $dRow->department_timing }}</td>
    <td>{{ $dRow->department_fees}}</td>
    <td>{{ $dRow->concession_fees }}</td>
    <td>{{CommonHelper::display_document($dRow->consession_fees_image)}}</td>
    <td>{{ $dRow->emp_name }}</td>
    <td>@if($dRow->status == 1) Active @else In-Active @endif</td>
</tr>
@endforeach
