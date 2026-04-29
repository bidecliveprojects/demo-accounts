@php
    use App\Helpers\CommonHelper;
@endphp
@extends('layouts.layouts')

@section('content')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="well_N">
				<div class="boking-wrp dp_sdw hr-page-card employee-form-page">
					<div class="row hr-page-head">
						<div class="col-lg-12">
							{{ CommonHelper::displayPageTitle('Sub menus') }}
							<p class="hr-page-lead text-muted">Link sub menu items to main navigation and URLs.</p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<h5 class="app-form-section-heading">Add sub menu</h5>
							<div class="row">
								<?php
								echo Form::open(array('url' => 'uad/addSubMenuDetail','id'=>'addSubMenuForm'));
								?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label for="menu_id">Main navigation name</label>
									<select class="form-control" name="menu_id" id="menu_id">
										<option value="">Select menu</option>
										@foreach($menus as $row)
											<option value="{{$row->id}}">{{$row->menu_name}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label for="sub_menu_type">Sub menu type</label>
									<select name="sub_menu_type" id="sub_menu_type" required class="form-control form-select select2 required">
										<option value="">Select sub menu type</option>
										<option value="1">Front nav</option>
										<option value="2">Inside page</option>
									</select>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label for="sub_menu_icon">Sub menu icon</label>
									<input type="text" id="sub_menu_icon" name="sub_menu_icon" required placeholder="Menu icon" class="form-control required">
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label for="sub_menu_name">Sub menu name</label>
									<input type="text" id="sub_menu_name" name="sub_menu_name" required placeholder="Sub menu name" class="form-control required">
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label for="url">URL</label>
									<input type="text" id="url" name="url" required placeholder="Controller path or URL" class="form-control required">
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hr-form-actions">
									{{ Form::submit('Submit', ['class' => 'btn btn-success btn-sm']) }}
									<button type="reset" id="reset" class="btn btn-default btn-sm">Clear</button>
								</div>
								<?php
								echo Form::close();
								?>
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							<h5 class="app-form-section-heading">Existing sub menus</h5>
							<div class="hr-table-wrap">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover sf-table-list hr-data-table">
										<thead>
											<tr>
												<th class="text-center">S.No</th>
												<th class="text-center">Main navigation</th>
												<th class="text-center">Sub navigation</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody id="viewSubMenuList">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
        $(document).ready(function() {
            function viewSubMenuList(){
                $('#viewSubMenuList').html('<tr><td colspan="4"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
                $.ajax({
                    url: '<?php echo url('/')?>/udc/viewSubMenuList',
                    type: "GET",
                    success:function(data) {
                        setTimeout(function(){
                            $('#viewSubMenuList').html(data);
                        },1000);
                    }
                });
            }

            $(function(){
                $('#addSubMenuForm').on('submit',function(e){
                    $.ajaxSetup({
                        header:$('meta[name="_token"]').attr('content')
                    })
                    e.preventDefault(e);

                    $.ajax({
                        type:"POST",
                        url:'<?php echo url('/')?>/uad/addSubMenuDetail',
                        data:$(this).serialize(),
                        dataType: 'json',
                        success: function(data){
                            alert(data);
                            console.log(data);
                        },
                        error: function(data){
                        }
                    })
                    $("#reset").click();
                });
            });
        });
	</script>
@endsection
