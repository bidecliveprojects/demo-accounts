<?php
	$counter = 1;
	$accType = auth()->user()?->acc_type ?? '';
	$companyNameMap = $companyNameMap ?? collect();
	$filterCompanyName = $filterCompanyName ?? '';

	foreach ($userDetails as $row){
		$companyNameForFilter = '';
		$ids = array_filter(explode('<*>', (string) ($row->company_id ?? '')));
		foreach ($ids as $ecdRow) {
			if ($ecdRow === '' || ! ctype_digit((string) $ecdRow)) {
				continue;
			}
			$cid = (int) $ecdRow;
			$nm = $companyNameMap[$cid] ?? $companyNameMap[$ecdRow] ?? null;
			if ($nm !== null && $nm !== '') {
				$companyNameForFilter .= ($companyNameForFilter !== '' ? ', ' : '') . $nm;
			}
		}
		$companyNameDisplay = $companyNameForFilter !== '' ? $companyNameForFilter : '—';

		if ((string) $row->status === '2' || (int) $row->status === 2) {
			$rowBColor = 'danger';
		}else{
			$rowBColor = '';
		}

		if ($accType === 'client') {
			$hiddenRow = '';
		} elseif ($filterCompanyName === '') {
			$hiddenRow = '';
		} elseif ($companyNameForFilter === '') {
			$hiddenRow = '';
		} elseif (strpos($companyNameForFilter, $filterCompanyName) !== false) {
			$hiddenRow = '';
		} else {
			$hiddenRow = 'hidden';
		}
?>
		<tr class="<?php echo $rowBColor.' '.$hiddenRow?>">
			<td class="text-center" ><?php echo $counter++  ?> </td>
			<td><?php echo e($companyNameDisplay); ?></td>
			<td><?php echo e($row->name ?? ''); ?></td>
			<td><?php echo e($row->username ?? ''); ?></td>
			<td><?php echo e($row->email ?? ''); ?></td>
			<td><?php echo e($row->mobile_no ?? ''); ?></td>
			<td><?php echo e($row->acc_type ?? ''); ?></td>
			<td class="text-center"><?php echo ((int) ($row->status ?? 0) === 1) ? 'Active' : 'Inactive'; ?></td>
			<td class="text-center" >
				@forelse($row->roles as $role)
					{{ $role->name }}{{ !$loop->last ? ', ' : '' }}
				@empty
					—
				@endforelse
			</td>
			<td class="text-center hidden-print">
				<div class="dropdown">
					<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions <span class="caret"></span></button>
					<ul class="dropdown-menu">
						<li><a onclick="showDetailModelTwoParamerter('udc/viewProfile','<?php echo (int) $row->id ?>','view User Profile','')"><span class="glyphicon glyphicon-eye-open"></span> View Profile</a></li>
						<?php if ((int) ($row->status ?? 0) === 1) { ?>
							<li><a onclick="showDetailModelTwoParamerter('udc/userEdit','<?php echo (int) $row->id ?>','User Edit Detail')"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
							<li><a onclick="showDetailModelTwoParamerter('udc/assignUserRoles','<?php echo (int) $row->id ?>','Assign roles')"><span class="glyphicon glyphicon-tags"></span> Assign roles</a></li>
							<li><a href="javascript:void(0)" onclick="setUserListStatus(<?php echo (int) $row->id; ?>, 2); return false;"><span class="glyphicon glyphicon-ban-circle"></span> Deactivate</a></li>
						<?php } else { ?>
							<li><a href="javascript:void(0)" onclick="setUserListStatus(<?php echo (int) $row->id; ?>, 1); return false;"><span class="glyphicon glyphicon-ok-circle"></span> Activate</a></li>
						<?php } ?>
					</ul>
				</div>
			</td>
		</tr>
<?php
	}
?>
<script>
$(document).ready(function(){
	userEnableDisableStatus();
});

 function userEnableDisableStatus(){
	 var userEnableDisableStatus = [];
	 $('input[name="checkboxes"]').each(function() {
		 var resign = $(this).val().split("<*>");

		var checkboxValues = $("#"+resign[0]).val();
		var checking2 = checkboxValues.split("<*>");
		if(checking2[1] == 1){
			 $("#"+resign[0]).prop("checked", true);
		}
		else if(checking2[1] == 2){
			 $("#"+resign[0]).prop("checked", false);
		}


		});


 }

function checkStausActive(status,userId){
	var url = '<?php echo url("/") ?>'+'/bd/checkStausActiveUser';
	$.ajax({
		type:'GET',
		url:url,
		data:{status:status,userId:userId},
		success:function(res){
			alert(res);
		}
	});

}

</script>
