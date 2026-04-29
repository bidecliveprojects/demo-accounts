<?php
/** @var \App\Models\User $userDetail */
$m = $m ?? \App\Facades\CommonFacades::getSessionCompanyId();
?>
<div class="row users-ajax-panel">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
        <?php echo CommonFacades::displayPrintButtonInBlade('PrintTentativeBookingForm','','1');?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="users-profile-print well-sm" id="PrintTentativeBookingForm">
            <?php echo CommonFacades::headerPrintSectionInPrintView($m);?>
            <div class="hr-table-wrap" style="margin-top:12px;">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped hr-data-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">User details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Username</th>
                                        <td><?php echo e($userDetail->username);?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Account type</th>
                                        <td><?php echo e($userDetail->acc_type); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td><?php echo e($userDetail->email);?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">CNIC No.</th>
                                        <td><?php echo e($userDetail->cnic_no); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped hr-data-table">
                                <thead>
                                    <tr>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Mobile No.</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo e($userDetail->mobile_no); ?></td>
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
