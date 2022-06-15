<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-program-form ">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="i-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
			<input id="i-idjadwal" type="hidden" name="i-idjadwal" value="<?php echo $idjadwal; ?>">
			<input id="i-idvisi" type="hidden" name="i-idvisi" value="<?php echo $idvisi; ?>">
			<input id="i-misikey" type="hidden" name="i-misikey" value="<?php echo $misikey; ?>">
			<input id="f-tujukey" type="hidden" name="f-tujukey" value="<?php echo $tujukey; ?>">
			<input id="i-idsasaran" type="hidden" name="i-idsasaran" value="<?php echo $idsasaran; ?>">
			<input id="f-idprogram" type="hidden" name="f-idprogram" value="<?php ?>">
			<input type="hidden" value="1" class="page">

			<div class="form-group">
				<label class="col-sm-2 control-label">Unit Organisasi</label>
				<div class="col-sm-2">
					<div class="input-group">
						<input type="hidden" value="<?php echo $this->session->KDUNIT; ?>" id="f-unitkey" name="f-unitkey" class="form-control text-bold" placeholder="Kode Unit" readonly>
					</div>
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Program</label>
				<div class="col-sm-2">
					<input type="hidden" id="v-kdprgrm" value="<?php echo $kdprgrm; ?>" class="form-control text-bold" readonly>
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-program"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmprgrm" value="<?php echo $nmprgrm; ?>" class="form-control text-bold" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator Program (outcome)</label>
				<div class="col-sm-10">
					<textarea name="i-indikator" class="form-control" rows="5"><?php echo $indikator; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Tahun</label>
				<div>
					<label class="col-sm-3 control-label" style="text-align:center">Target</label>
					<label class="col-sm-3 control-label" style="text-align:center">Satuan</label>
					<label class="col-sm-4 control-label" style="text-align:center">Pagu</label>
				</div>
			</div>

			<?php for($i=0; $i <= $length; $i++)  { ?>
				<?php if($i==0){?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Awal</label>
						<input type="hidden" name="i-tahun" class="form-control" rows="5" value="<?php ?>"></input>
						<div class="col-sm-3">
							<input name="i-satuan" class="form-control" rows="5" value="<?php ?>"></input>
						</div>
						<div class="col-sm-3">
							<input name="i-pagu" class="form-control" rows="5" value="<?php ?>"></input>
						</div>
					</div>	
				<?php } ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo $tahun+$i;?></label>
					<input type="hidden" name="i-tahun<?php echo $i;?>" class="form-control" rows="5" value="<?php ?>"></input>
					<div class="col-sm-3">
						<input name="i-target<?php echo $i;?>" class="form-control" rows="5" value="<?php ?>"></input>
					</div>
					<div class="col-sm-3">
						<input name="i-satuan<?php echo $i;?>" class="form-control" rows="5" value="<?php ?>"></input>
					</div>
					<div class="col-sm-4">
						<input name="i-pagu<?php echo $i;?>" class="form-control" rows="5" value="<?php ?>"></input>
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
var blockProgramForm = '.block-program-form ';
$(function() {
	updateMask(blockProgramForm);

	$(document).off('click', blockProgramForm + '.btn-lookup-program');
	$(document).on('click', blockProgramForm + '.btn-lookup-program', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		var data = {
			'l-unitkey' : getVal('#f-unitkey'),
			'setid'	: '#i-pgrmrkpdkey',
			'setkd'	: '#v-kdprgrm',
			'setnm'	: '#v-nmprgrm',
		}
		console.log(data);
		modalLookupProgram = new BootstrapDialog({
			title: 'Lookup Program',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/program/', data)
		});
		modalLookupProgram.open();

		return false;
	});

	$(document).off('submit', blockProgramForm + '.form-program');
	$(document).on('submit', blockProgramForm + '.form-program', function(e) {
		e.preventDefault();
		$.post('/rpjmd/program_save/<?php echo $act; ?>', $(blockProgramForm + '.form-program').serializeArray(), function(res, status, xhr) {
			console.log(blockProgramForm);
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
