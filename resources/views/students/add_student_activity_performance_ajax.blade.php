@php
    $counter = 1;
@endphp
@foreach($getStudentDetail as $gsdRow)
    <tr>
        <td class="text-center">{{$counter++}}</td>
        <td>{{$gsdRow->registration_no}}</td>
        <td>{{$gsdRow->student_name}}</td>
        <td>{{$gsdRow->para_name}}</td>
        <td>
            <input type="hidden" name="student_id_array[]" id="student_id_array" value="{{$gsdRow->id}}" />
            <input type="hidden" name="sabqi_para_id_{{$gsdRow->id}}" id="sabqi_para_id_{{$gsdRow->id}}" value="{{$gsdRow->para_id}}" />
            <select name="sabqi_para_performance_{{$gsdRow->id}}" id="sabqi_para_performance_{{$gsdRow->id}}" class="form-control">
                @foreach($levelOfPerformances as $lopRow)
                    <option value="{{$lopRow->id}}">{{$lopRow->performance_name}}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="manzil_performance_{{$gsdRow->id}}" id="manzil_performance_{{$gsdRow->id}}" class="form-control">
                @foreach($levelOfPerformances as $lopRow)
                    <option value="{{$lopRow->id}}">{{$lopRow->performance_name}}</option>
                @endforeach
            </select>
        </td>
        @foreach($heads as $hRow)
            <td>
                <select name="{{strtolower(str_replace(' ', '_', $hRow->head_name))}}_{{$gsdRow->id}}" id="{{strtolower(str_replace(' ', '_', $hRow->head_name))}}_{{$gsdRow->id}}" class="form-control">
                    @foreach($levelOfPerformances as $lopRow)
                        <option value="{{$lopRow->id}}">{{$lopRow->performance_name}}</option>
                    @endforeach
                </select>
            </td>
        @endforeach
    </tr>
@endforeach
@if(count($getStudentDetail) != 0)
    @php
        $totalColspan = count($heads) + 5;
    @endphp
    <tr>
        <td colspan="{{$totalColspan}}" class="text-center">---</td>
        <td class="text-center"><button type="submit" class="btn btn-sm btn-success btnSubmit">Submit</button></td>
    </tr>
@endif