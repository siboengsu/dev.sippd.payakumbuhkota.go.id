<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-sasaran-form">
	<div class="col-md-12">
		<form class="form-horizontal form-sasaran">
			<input id="i-id" type="hidden" name="i-id" value="<?php echo $id?>">
			<input id="f-tujukey" type="hidden" name="f-tujukey" value="<?php echo $tujukey?>">
			<input type="hidden" value="1" class="page">

			<div class="form-group">
				<label class="col-sm-2 control-label">No. Sasaran</label>
				<div class="col-sm-10">
					<input name="i-nosasaran" class="form-control" rows="5" value="<?php echo $nosasaran; ?>"></input>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Sasaran</label>
				<div class="col-sm-10">
					<input name="i-sasaran" class="form-control" rows="5" value="<?php echo $sasaran; ?>"></input>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator Sasaran</label>
				<div class="col-sm-10">
					<textarea name="i-indikator" class="form-control" rows="5"><?php ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Tahun Ke</label>
				<div>
					<label class="col-sm-5 control-label" style="text-align:center">Target</label>
					<label class="col-sm-5 control-label" style="text-align:center">Satuan</label>
				</div>
			</div>

			<?php for($i=0; $i < $row; $i++) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo ($i+1);?></label>
					<input type="hidden" name="i-tahun<?php echo ($i+1);?>" class="form-control" rows="5" value="<?php echo ($i+1);?>"></input>
					<div class="col-sm-5">
						<input name="i-target<?php echo ($i+1);?>" class="form-control" rows="5" value=""></input>
					</div>
					<div class="col-sm-5">
						<input name="i-satuan<?php echo ($i+1);?>" class="form-control" rows="5" value=""></input>
					</div>
				</div>
			<?php } ?>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10 text-right">
					<button type="submit" class="btn btn-success<?php echo $curdShow; ?>"><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
var blockSasaranForm = '.block-sasaran-form ';
$(function() {
	updateMask(blockSasaranForm);

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
