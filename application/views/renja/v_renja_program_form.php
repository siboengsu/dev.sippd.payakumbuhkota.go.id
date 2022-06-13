<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$kdtahap = $this->KDTAHAP = $this->session->KDTAHAP;
?>
<div class="row block-program-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="i-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
			<input type="hidden" id="i-prioppaskey" name="i-prioppaskey" value="<?php echo $prioppaskey; ?>">
			<input type="hidden" id="i-idsas" name="i-idsas" value="<?php echo $idsas; ?>">

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
				<label class="col-sm-2 control-label">IKU (OPD)<br><br> Tolak Ukur</label>
				<div class="col-sm-10">
					<textarea name="i-indikator" class="form-control" rows="5"><?php echo $indikator; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Target</label>
				<div class="col-sm-10">
					<textarea name="i-sasaran" class="form-control" rows="5"><?php echo $sasaran; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator Program <br><br> Tolak Ukur</label>
				<div class="col-sm-10">
					<textarea name="i-tolokur" class="form-control" rows="5"><?php echo $tolokur; ?></textarea>
				</div>
			</div>
			<div class="form-group" style="<?php if ($kdtahap==4){echo "display:none";} ?>">
				<label class="col-sm-2 control-label">Target Sebelum</label>
				<div class="col-sm-10">
					<input type="text" name="i-targetsebelum" value="<?php echo $targetsebelum; ?>" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" style="<?php if ($kdtahap==4){$targe = "Target";} else {$targe = "Target Sesudah";} ?>"><?php echo $targe;?></label>
				<div class="col-sm-10">
					<input type="text" name="i-target" value="<?php echo $target; ?>" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Prioritas Daerah</label>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary btn-lookup-prioritas"><i class="fa fa-folder-open"></i> <b>Tambah Prioritas Daerah</b></button>
						</span>
						</div>
				</div>
			</div>

			<div class="form-group">
					<label class="col-sm-2 control-label">Prioritas Provinsi</label>
					<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-danger btn-lookup-prioritas-Provinsi"><i class="fa fa-folder-open"></i> <b>Tambah Prioritas Provinsi</b></button>
						</span>
						</div>

				</div>

			</div>


			<div class="form-group">
				<label class="col-sm-2 control-label">Prioritas Nasional</label>
				<div class="form-gap visible-xs-block"></div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-btn">
						<button type="button" class="btn btn-warning btn-lookup-prioritas-Nasional"><i class="fa fa-folder-open"></i> <b>Tambah Prioritas Nasional</b></button>
					</span>
					</div>

			</div>

			</div>



			<?php if($this->sip->is_admin()): ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tanggal Valid</label>
				<div class="col-sm-2">
					<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#toggle-pengesahan"><i class="fa fa-calendar-check-o"></i> Pengesahan</button>
				</div>
				<div class="col-sm-4">
					<div class="collapse" id="toggle-pengesahan">
						<div class="input-group flatpickr">
							<input type="text" name="i-tglvalid" id="i-tglvalid" value="<?php echo $tglvalid; ?>" class="form-control text-center" placeholder="Pilih Tanggal" data-input>
							<div class="input-group-btn">
								<button type="button" class="btn btn-default" data-toggle><i class="fa fa-calendar"></i></button>
								<button type="button" class="btn btn-default" data-clear><i class="fa fa-times"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success <?php echo $curdShow; ?>"><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>

		</form>
	</div>
</div>
<script>
var blockProgramForm = '.block-program-form ';

$(function() {
	updateDate(blockProgramForm);

	$(document).off('click', blockProgramForm + '.btn-lookup-program');
	$(document).on('click', blockProgramForm + '.btn-lookup-program', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#i-unitkey'))) return false;
		var data = {
			'l-unitkey' : getVal('#i-unitkey'),
			'setid'	: '#i-pgrmrkpdkey',
			'setkd'	: '#v-kdprgrm',
			'setnm'	: '#v-nmprgrm'
		}
		modalLookupProgram = new BootstrapDialog({
			title: 'Lookup Program',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/program/', data)
		});
		modalLookupProgram.open();

		return false;
	});

	$(document).off('click', blockProgramForm + '.btn-lookup-prioritas');
	$(document).on('click', blockProgramForm + '.btn-lookup-prioritas', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#i-pgrmrkpdkey'))) return false;
		var data = {
				'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')
		};

		modalLookupPrioritas = new BootstrapDialog({
			title: 'Lookup Prioritas Daerah',
			type: 'type-primary',
			size: 'size-wide',
			message: $('<div></div>').load('/renja/prioritas_form/', data)
		});
		modalLookupPrioritas.open();

		return false;
	});

	$(document).off('change', '#i-prioppaskey');
	$(document).on('change', '#i-prioppaskey', function(e) {
		e.preventDefault();
		$('#i-idsas, #v-nosas, #v-nmsas').val('');
	});

	$(document).off('click', blockProgramForm + '.btn-lookup-prioritas-Provinsi');
	$(document).on('click', blockProgramForm + '.btn-lookup-prioritas-Provinsi', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#i-pgrmrkpdkey'))) return false;
		var data = {
				'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')
		};

		modalLookupPrioritasProvinsi = new BootstrapDialog({
			title: 'Lookup Prioritas Provinsi',
			type: 'type-primary',
			size: 'size-wide',
			message: $('<div></div>').load('/renja/prioritas_form_provinsi/', data)
		});
		modalLookupPrioritasProvinsi.open();

		return false;
	});

	$(document).off('click', blockProgramForm + '.btn-lookup-prioritas-Nasional');
	$(document).on('click', blockProgramForm + '.btn-lookup-prioritas-Nasional', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#i-pgrmrkpdkey'))) return false;
		var data = {
				'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')
		};

		modalLookupPrioritasNasional = new BootstrapDialog({
			title: 'Lookup Prioritas Nasional',
			type: 'type-primary',
			size: 'size-wide',
			message: $('<div></div>').load('/renja/prioritas_form_nasional/', data)
		});
		modalLookupPrioritasNasional.open();

		return false;
	});

	$(document).off('change', '#i-prioppaskey');
	$(document).on('change', '#i-prioppaskey', function(e) {
		e.preventDefault();
		$('#i-idsas, #v-nosas, #v-nmsas').val('');
	});

	$(document).off('click', blockProgramForm + '.btn-lookup-sasaran');
	$(document).on('click', blockProgramForm + '.btn-lookup-sasaran', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#i-prioppaskey'))) return false;
		var data = {
			'l-prioppaskey' : getVal('#i-prioppaskey'),
			'setid' : '#i-idsas',
			'setkd' : '#v-nosas',
			'setnm' : '#v-nmsas'
		}

		modalLookupSasaran = new BootstrapDialog({
			title: 'Lookup Sasaran',
			type: 'type-primary',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/sasaran/', data)
		});
		modalLookupSasaran.open();

		return false;
	});

	$(document).off('submit', blockProgramForm + '.form-program');
	$(document).on('submit', blockProgramForm + '.form-program', function(e) {
		e.preventDefault();
		$.post('/renja/program_save/<?php echo $act; ?>', $(blockProgramForm + '.form-program').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalProgramForm.close();
				dataLoadProgram();
			}
		});

		return false;
	});
});
</script>
