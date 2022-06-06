<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-tahap">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Pindah Tahapan</h1>
		</div>
	</div>
	
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Tahapan</div>
			<div class="panel-body">
				<form class="form-horizontal form-tahap">
					
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
						<label class="control-label col-sm-2">Tahapan</label>
						<div class="col-sm-10">
							<select name="i-kdtahap" id="i-kdtahap" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" title="Pilih Tahap">
								<?php foreach($tahap as $r):
								$r = settrim($r); ?>
								<option value="<?php echo $r['KDTAHAP']; ?>" <?php echo setselected($r['KDTAHAP'], $this->session->KDTAHAP); ?>><?php echo $r['NMTAHAP']; ?></option>
								<?php endforeach; ?>
							</select>
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
var blockTahap = '.block-tahap ';

$(function() {
	updateSelect();
	
	$(document).off('click', blockTahap + '.btn-lookup-user');
	$(document).on('click', blockTahap + '.btn-lookup-user', function(e) {
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
	
	$(document).off('submit', blockTahap + '.form-tahap');
	$(document).on('submit', blockTahap + '.form-tahap', function(e) {
		e.preventDefault();
		$.post('/user/tahap_save/', $(blockTahap + '.form-tahap').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			}
		});
		
		return false;
	});
});
</script>

