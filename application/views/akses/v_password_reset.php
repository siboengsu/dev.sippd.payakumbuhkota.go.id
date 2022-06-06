<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-reset">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Reset Password</h1>
		</div>
	</div>
	
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Tahapan</div>
			<div class="panel-body">
				<form class="form-horizontal form-reset">
					
					<input type="hidden" name="i-userid" id="i-userid" value="<?php echo $this->session->USERID; ?>">
					
					<div class="form-group">
						<label class="col-sm-2 control-label">User ID</label>
						<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-user"></i>
								</span>
								<input type="text" id="v-userid" value="<?php echo $this->session->USERID; ?>" class="form-control text-bold" placeholder="User ID" readonly>
							</div>
						</div>
						
						<div class="form-gap visible-xs-block"></div>
						
						<div class="col-sm-7">
							<?php if($this->sip->is_admin()): ?>
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-lookup-user"><i class="fa fa-folder-open"></i></button>
								</span>
								<input type="text" id="v-nama" value="<?php echo $this->session->NAMA; ?>" class="form-control text-bold" placeholder="Nama" readonly>
							</div>
							<?php else: ?>
							<input type="text" id="v-nama" value="<?php echo $this->session->NAMA; ?>" class="form-control text-bold" placeholder="Nama" readonly>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2">Password Baru</label>
						<div class="col-sm-10">
							<input type="password" name="i-phppwd_new" id="i-phppwd_new" class="form-control text-bold" placeholder="Password Baru">
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2">Password Baru (Konfirmasi)</label>
						<div class="col-sm-10">
							<input type="password" name="i-phppwd_conf" id="i-phppwd_conf" class="form-control text-bold" placeholder="Password Baru (Konfirmasi)">
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-success <?php $this->sip->curdShow('U'); ?>"><i class="fa fa-download"></i> Simpan</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
var blockReset = '.block-reset ';

$(function() {
	updateSelect();
	
	$(document).off('click', blockReset + '.btn-lookup-user');
	$(document).on('click', blockReset + '.btn-lookup-user', function(e) {
		e.preventDefault();
		var data = {
			setid : '#i-userid, #v-userid',
			setnm : '#i-nama'
		}
		
		modalLookupUser = new BootstrapDialog({
			title: 'Lookup User',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/user/', data)
		});
		modalLookupUser.open();
		
		return false;
	});
	
	$(document).off('submit', blockReset + '.form-reset');
	$(document).on('submit', blockReset + '.form-reset', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#i-userid'))) return false;
		$.post('/user/password_reset_save/', $(blockReset + '.form-reset').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				goAlert({
					type: 'success',
					msg: 'Password berhasil diperbaharui.'
				});
				
				$('#i-phppwd_new, #i-phppwd_conf').val('');
			}
		});
		
		return false;
	});
});
</script>

