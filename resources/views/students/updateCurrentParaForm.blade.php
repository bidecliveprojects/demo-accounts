<div class="well">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>Registration No</th>
                                    <td>{{$currentParaDetail->registration_no}}</td>
                                </tr>
                                <tr>
                                    <th>Student Name</th>
                                    <td>{{$currentParaDetail->student_name}}</td>
                                </tr>
                                <tr>
                                    <th>Current Para Name</th>
                                    <td>{{$currentParaDetail->para_name ?? '-'}}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <form method="POST" action="{{ route('updateCurrentParaDetail') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Para Name</label>
                        <select class="form-control" name="para_id" id="para_id">
                            <option value="">Select Para</option>
                            @foreach($remainingParasList as $plRow)
                                <option value="{{$plRow->id}}">{{$plRow->para_name}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="student_id" id="student_id" value="{{$currentParaDetail->studentId}}" />
                        <input type="hidden" name="privious_para_id" id="privious_para_id" value="{{$currentParaDetail->para_id}}" />
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
