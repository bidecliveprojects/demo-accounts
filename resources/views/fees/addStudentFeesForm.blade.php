<div class="well">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    Student Information
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="floatLeft">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Registration No</th>
                                                <td>{{$student->registration_no}}</td>
                                            </tr>
                                            <tr>
                                                <th>Date of Admission</th>
                                                <td>{{$student->date_of_admission}}</td>
                                            </tr>
                                            <tr>
                                                <th>Student Name</th>
                                                <td>{{$student->student_name}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <form method="POST" action="{{ route('fees.addStudentFeesDetail') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="student_id" id="student_id" value="{{$student->id}}" />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Month-Year</label>
                        <input type="date" name="month_year" id="month_year" class="form-control" required />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Fee Amount</label>
                        <input type="number" readonly name="amount" id="amount" value="{{$student->department_fees - $student->concession_fees}}" class="form-control" />
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
