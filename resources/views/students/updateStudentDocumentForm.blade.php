@php
    use App\Helpers\CommonHelper;
    $documentArray = array(
        'birth_certificate',
        'father_guardian_cnic',
        'father_guardian_cnic_back',
        'passport_size_photo',
        'copy_of_last_report',
        'consession_fees_image'
    );
@endphp
@extends('layouts.layouts')
@section('content')
    <div class="well_N">
        <div class="boking-wrp dp_sdw">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    {{CommonHelper::displayPageTitle('Update Student Document Detail')}}
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                    <a href="{{ route('students.index') }}" class="btn btn-success btn-xs">+ View List</a>
                </div>
            </div>
            <div class="row">
                <form method="POST" action="{{ route('students.updateStudentDocumentDetail',$student->id) }}" enctype="multipart/form-data">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @csrf
                        @php
                            $counter = 0;
                        @endphp
                        @foreach ($documentArray as $daRow)
                            @php
                                $counter++;
                            @endphp
                            <div class="row">
                                <input type="hidden" name="fieldArray[]" id="fieldArray" value="{{$counter}}" />
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <select name="option_{{$counter}}" id="option_{{$counter}}" onchange="optionEnableDisableImage('{{$counter}}')" class="form-control">
                                        <option value="2">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    @if($daRow == 'consession_fees_image')
                                        {{CommonHelper::display_document($student->$daRow)}}
                                    @else
                                        {{CommonHelper::display_document($student->student_document->$daRow)}}
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <input type="hidden" name="column_name_{{$counter}}" id="column_name_{{$counter}}" value="{{$daRow}}" />
                                    <input type="file" name="image_{{$counter}}" id="image_{{$counter}}" disabled class="form-control" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function optionEnableDisableImage(param){
            var option = $('#option_'+param+'').val();
            if(option == 1){
                $('#image_'+param+'').removeAttr('disabled');
                $('#image_'+param+'').attr('required','required');
            }else{
                $('#image_'+param+'').attr('disabled','disabled');
                $('#image_'+param+'').removeAttr('required');
            }
        }
    </script>
@endsection
