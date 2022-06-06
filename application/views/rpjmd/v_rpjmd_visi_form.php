<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-visi-form">
	<div class="col-md-12">
		<form class="form-horizontal form-visi">
			<input id="i-idvisi" type="hidden" name="i-idvisi" value="<?php echo $idvisi?>">
			<input id="i-idjadwal" type="hidden" name="i-idjadwal" value="<?php echo $idjadwal; ?>">
			<input type="hidden" value="1" class="page">
			<div class="form-group">
				<label class="col-sm-2 control-label">No Visi</label>
				<div class="col-sm-10">
					<input name="i-novisi" class="form-control" rows="5" value="<?php echo $novisi; ?>"></input>
				</div>
			</div>
            <div class="form-group">
				<label class="col-sm-2 control-label">Visi</label>
				<div class="col-sm-10">
					<textarea name="i-nmvisi" class="form-control" rows="5"><?php echo $nmvisi; ?></textarea>
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
var blockVisiForm = '.block-visi-form ';
$(function() {
	updateMask(blockVisiForm);

	$(document).off('submit', blockVisiForm + '.form-visi');
	$(document).on('submit', blockVisiForm + '.form-visi', function(e) {
		e.preventDefault();
		$.post('/rpjmd/visi_save/<?php echo $act; ?>', $(blockVisiForm + '.form-visi').serializeArray(), function(res, status, xhr) {
			console.log(blockVisiForm);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalVisiForm.close();
				dataLoadVisi();
			}
		});
		return false;
	});
});
</script>
