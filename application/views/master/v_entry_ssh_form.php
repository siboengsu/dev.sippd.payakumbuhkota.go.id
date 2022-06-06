<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-ssh-form">
	<div class="col-md-12">
		<form class="form-horizontal form-ssh-entry">
				<input id="i-kdssh" type="hidden" name="i-kdssh" value="<?php echo $kdssh; ?>">
				<input type="hidden" value="1" class="page">

							<div class="form-group">
								<label class="col-sm-2 control-label">Kode Rekening</label>
								<div class="col-sm-5">
										<div class="input-group">
											<span class="input-group-btn">
												<button type="button" class="btn btn-default btn-lookup-rek"><i class="fa fa-folder-open"></i></button>
											</span>
											<input type="text" name="i-kdrek" id="i-kdrek" value="<?php echo $kdrek; ?>" class="form-control text-bold" readonly>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default btn-remove-rek"><i class="fa fa-times"></i></button>
											</span>
										</div>
									</div>
								</div>

								<div class="form-group">
								<label class="col-sm-2 control-label">Uraian</label>
								<div class="col-sm-8">
										<input type="text" name="i-nmper" id="i-nmper" value="<?php echo $nmper; ?>"  class="form-control" readonly>


								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Nama</label>
								<div class="col-sm-10">
										<textarea name="i-ssh_nama" class="form-control" rows="5"><?php echo $ssh_nama; ?></textarea>


								</div>
							</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Spesifikasi</label>
							<div class="col-sm-10">
								<textarea name="i-ssh_spek" class="form-control" rows="5"><?php echo $ssh_spek; ?></textarea>

							</div>
						</div>

							<div class="form-group">
							<label class="col-sm-2 control-label">Satuan</label>
							<div class="col-sm-2">
								<input type="text" name="i-ssh_satuan" value="<?php echo $ssh_satuan ?>" class="form-control">
							</div>
						</div>

							<div class="form-group">
							<label class="col-sm-2 control-label">Harga Satuan</label>
							<div class="col-sm-4">
								<input type="text" name="i-ssh_harga" value="<?php echo $ssh_harga ?>" class="form-control mask-nu2d">
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-success <?php echo $curdShow; ?>"><i class="fa fa-download"></i> Simpan</button>
							</div>
						</div>

			</form>
	</div>
</div>




<script>
var blockSSHForm = '.block-ssh-form ';

$(function() {
	updateMask(blockSSHForm);

	$(document).off('submit', blockSSHForm + '.form-ssh-entry');
	$(document).on('submit', blockSSHForm + '.form-ssh-entry', function(e) {
		e.preventDefault();

		$.post('/master/ssh_save/<?php echo $act; ?>', $(blockSSHForm + '.form-ssh-entry').serializeArray(), function(res, status, xhr) {
			console.log(blockSSHForm);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalEntrySSHForm.close();
				dataLoadMasterSSH();
			}
		});

		return false;
	});

	$(document).off('click', blockSSHForm + '.btn-lookup-rek');
	$(document).on('click', blockSSHForm + '.btn-lookup-rek', function(e) {
		e.preventDefault();
		var data = {
			
			'setkd' : '#i-kdrek',
			'setnm' : '#i-nmper'
			

		};

		modalLookupRekeningSSH = new BootstrapDialog({
			title: 'Lookup Rekening',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/master/rekeningSSH/', data)
		});
		modalLookupRekeningSSH.open();

		return false;
	});



	$(document).off('click', blockSSHForm + '.btn-remove-rek');
	$(document).on('click', blockSSHForm + '.btn-remove-rek', function(e) {

		$('#i-kdrek').val('');
		$('#i-uraian').val('').prop('readonly', false);


		return false;
	});


});
</script>
