<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-misi-form">
	<div class="col-md-12">
		<form class="form-horizontal form-misi">
			<input id="i-idvisi" type="hidden" name="i-idvisi" value="<?php echo $idvisi?>">
			<input id="i-misikey" type="hidden" name="i-misikey" value="<?php echo $misikey?>">
			<input type="hidden" value="1" class="page">
			<div class="form-group">
				<label class="col-sm-2 control-label">No Misi</label>
				<div class="col-sm-10">
					<input name="i-nomisi" class="form-control" rows="5" value="<?php echo $nomisi; ?>"></input>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Misi</label>
				<div class="col-sm-10">
					<input name="i-uraimisi" class="form-control" rows="5" value="<?php echo $uraimisi; ?>"></input>
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
var blockMisiForm = '.block-misi-form ';
$(function() {
	updateMask(blockMisiForm);

	$(document).off('submit', blockMisiForm + '.form-misi');
	$(document).on('submit', blockMisiForm + '.form-misi', function(e) {
		e.preventDefault();
		$.post('/rpjmd/misi_save/<?php echo $act; ?>', $(blockMisiForm + '.form-misi').serializeArray(), function(res, status, xhr) {
			console.log(blockMisiForm);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalMisiForm.close();
				dataLoadMisi();
			}
		});
		return false;
	});
});
</script>
