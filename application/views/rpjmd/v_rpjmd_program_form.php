<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-program-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php ?>">
			<input id="i-idjadwal" type="hidden" name="i-idjadwal" value="<?php ?>">
			<input id="i-idvisi" type="hidden" name="i-idvisi" value="<?php ?>">
			<input id="i-misikey" type="hidden" name="i-misikey" value="<?php ?>">
			<input id="f-tujukey" type="hidden" name="f-tujukey" value="<?php ?>">
			<input id="i-idsasaran" type="hidden" name="i-idsasaran" value="<?php ?>">
			<input id="f-idprogram" type="hidden" name="f-idprogram" value="<?php ?>">
			<input type="hidden" value="1" class="page">

			<div class="form-group">
				<label class="col-sm-2 control-label">Unit Organisasi</label>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
						<input type="text" value="<?php echo $this->session->KDUNIT; ?>" id="v-kdunit" class="form-control text-bold" placeholder="Kode Unit" readonly>
					</div>
				</div>

				<div class="form-gap visible-xs-block"></div>

				<div class="col-sm-8">
					<?php if($this->sip->is_admin()): ?>
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
					</div>
					<?php else: ?>
					<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Program</label>
				<div class="col-sm-2">
					<input type="text" id="v-kdprgrm" value="<?php echo $kdprgrm; ?>" class="form-control text-bold" readonly>
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-program"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmprgrm" value="<?php echo $nmprgrm; ?>" class="form-control text-bold" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Sasaran</label>
				<div class="col-sm-10">
					<input name="i-sasaran" class="form-control" rows="5" value="<?php ?>"></input>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator Sasaran</label>
				<div class="col-sm-10">
					<textarea name="i-indikator" class="form-control" rows="5"><?php ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Tahun</label>
				<div>
					<label class="col-sm-5 control-label" style="text-align:center">Target</label>
					<label class="col-sm-5 control-label" style="text-align:center">Satuan</label>
				</div>
			</div>

			<?php for($i=0; $i <= 3; $i++)  { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php ?></label>
					<input type="hidden" name="i-tahun<?php ?>" class="form-control" rows="5" value="<?php ?>"></input>
					<div class="col-sm-5">
						<input name="i-target<?php ?>" class="form-control" rows="5" value="<?php ?>"></input>
					</div>
					<div class="col-sm-5">
						<input name="i-satuan<?php ?>" class="form-control" rows="5" value="<?php ?>"></input>
					</div>
				</div>
			<?php } ?>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10 text-right">
					<button type="submit" class="btn btn-success<?php ?>"><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
var blockProgramForm = '.block-sasaran-form ';
$(function() {
	updateMask(blockSasaranForm);

	$(document).off('click', blockProgramForm + '.btn-lookup-program');
	$(document).on('click', blockProgramForm + '.btn-lookup-program', function(e) {
		e.preventDefault();
		// if(isEmpty(getVal('#i-unitkey'))) return false;
		var data = {
			'l-unitkey' : "58_",
			'setid'	: '#i-pgrmrkpdkey',
			'setkd'	: '#v-kdprgrm',
			'setnm'	: '#v-nmprgrm'
		}

		console.log(data);

		modalLookupProgram = new BootstrapDialog({
			title: 'Lookup Program',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/program/', data)
		});
		modalLookupProgram.open();

		// return false;
	});

	$(document).off('submit', blockSasaranForm + '.form-sasaran');
	$(document).on('submit', blockSasaranForm + '.form-sasaran', function(e) {
		e.preventDefault();
		$.post('/rpjmd/sasaran_save/<?php echo $act; ?>', $(blockSasaranForm + '.form-sasaran').serializeArray(), function(res, status, xhr) {
			console.log(blockSasaranForm);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalSasaranForm.close();
				dataLoadSasaran();
			}  
		});
		return false;
	});
});
</script>
