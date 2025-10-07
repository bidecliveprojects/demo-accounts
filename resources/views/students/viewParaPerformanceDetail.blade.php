<?php
    use App\Helpers\CommonHelper;
?>
<div class="well">
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
                                    <tr>
                                        <th>Date of Birth</th>
                                        <td>{{$student->date_of_birth}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="floatRight">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Previous Madrasa</th>
                                        <td>{{$student->previous_madrasa}}</td>
                                    </tr>
                                    <tr>
                                        <th>Grade Class Applied For</th>
                                        <td>{{$student->grade_class_applied_for}}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference</th>
                                        <td>{{$student->reference}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            PARENT AND GUARDIAN INFORMATION
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Father Name</th>
                                    <td>{{$student->student_guardian_information->father_name}}</td>
                                </tr>
                                <tr>
                                    <th>Father Qualification</th>
                                    <td>{{$student->student_guardian_information->father_qualification}}</td>
                                </tr>
                                <tr>
                                    <th>Father Occupation</th>
                                    <td>{{$student->student_guardian_information->father_occupation}}</td>
                                </tr>
                                <tr>
                                    <th>Mobile Number</th>
                                    <td>{{$student->student_guardian_information->mobile_no}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Mother Name</th>
                                    <td>{{$student->student_guardian_information->mother_name}}</td>
                                </tr>
                                <tr>
                                    <th>Mother Qualification</th>
                                    <td>{{$student->student_guardian_information->mother_qualification}}</td>
                                </tr>
                                <tr>
                                    <th>Mother Tongue</th>
                                    <td>{{$student->student_guardian_information->mother_tongue}}</td>
                                </tr>
                                <tr>
                                    <th>Parent Email</th>
                                    <td>{{$student->student_guardian_information->parent_email}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>City</th>
                                    <td>{{$student->city_name}}</td>
                                </tr>
                                <tr>
                                    <th>CNIC No.</th>
                                    <td>{{$student->student_guardian_information->cnic_no}}</td>
                                </tr>
                                <tr>
                                    <th>Home Address</th>
                                    <td>{{$student->student_guardian_information->home_address}}</td>
                                </tr>
                                <tr>
                                    <th>Specify Any Health Problem Medication</th>
                                    <td>{{$student->student_guardian_information->specify_any_health_problem_medication}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            DOCUMENT REQUIRED WITH REGISTRATION FORM
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-sm-12">
                    {{CommonHelper::display_document($student->student_document->birth_certificate)}}
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-sm-12">
                    {{CommonHelper::display_document($student->student_document->father_guardian_cnic)}}
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-sm-12">
                    {{CommonHelper::display_document($student->student_document->father_guardian_cnic_back)}}
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-sm-12">
                    {{CommonHelper::display_document($student->student_document->passport_size_photo)}}
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-sm-12">
                    {{CommonHelper::display_document($student->student_document->copy_of_last_report)}}
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            MADARSA TIMING AND FEES
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="floatLeft">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Student Department</th>
                                        <td>{{$student->registration_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>Class Timing</th>
                                        <td>{{$student->student_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Fees</th>
                                        <td>{{$student->date_of_admission}}</td>
                                    </tr>
                                    <tr>
                                        <th>Concession Fees</th>
                                        <td>{{$student->date_of_birth}}</td>
                                    </tr>
                                    <tr>
                                        <th>Class Teacher Name</th>
                                        <td>{{$student->date_of_birth}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="floatRight">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {{CommonHelper::display_document($student->consession_fees_image)}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
