<?php
	use App\Helpers\CommonHelper;
?>
@extends('layouts.layouts')
@section('content')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="well_N">
				<div class="boking-wrp dp_sdw hr-page-card employee-form-page">
					<div class="row hr-page-head">
						<div class="col-lg-12">
							{{ CommonHelper::displayPageTitle('Main menu titles') }}
							<p class="hr-page-lead text-muted">Define navigation groups and review existing menu titles.</p>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<h5 class="app-form-section-heading">Add main menu title</h5>
							<div class="row">
								<?php
									echo Form::open(array('url' => 'uad/addMainMenuTitleDetail','id'=>'addMainMenuTitleForm'));
								?>
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="menu_type">Menu type</label>
										<select name="menu_type" id="menu_type" required class="form-select select2 required form-control">
											@foreach($menuType as $key => $mtRow)
												<option value="{{$key}}">{{$mtRow}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="menu_icon">Menu icon</label>
										<input type="text" id="menu_icon" name="menu_icon" required placeholder="Icon class or identifier" class="form-control required">
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="title_name">Sub navigation title name</label>
										<input type="text" name="title_name" id="title_name" value="" class="form-control" />
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
							<h5 class="app-form-section-heading">Existing titles</h5>
							<div class="hr-table-wrap">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover sf-table-list hr-data-table">
										<thead>
											<tr>
												<th class="text-center">S.No</th>
												<th class="text-center">Main navigation</th>
												<th class="text-center">Sub navigation title</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody id="viewMainMenuTitleList">
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
@endsection

