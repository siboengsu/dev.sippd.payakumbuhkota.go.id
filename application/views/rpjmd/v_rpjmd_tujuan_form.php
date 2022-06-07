<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-tujuan-form">
	<div class="col-md-12">
		<form class="form-horizontal form-tujuan">
			<input id="i-misikey" type="hidden" name="i-misikey" value="<?php echo $misikey?>">
			<input id="i-tujukey" type="hidden" name="i-tujukey" value="<?php echo $tujukey?>">
			<input type="hidden" value="1" class="page">
			<div class="form-group">
				<label class="col-sm-2 control-label">No Tujuan</label>
				<div class="col-sm-10">
					<input name="i-notuju" class="form-control" rows="5" value="<?php echo $notuju; ?>"></input>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tujuan</label>
				<div class="col-sm-10">
					<input name="i-uraituju" class="form-control" rows="5" value="<?php echo $uraituju; ?>"></input>
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
var blockTujuanForm = '.block-tujuan-form ';
$(function() {
	updateMask(blockTujuanForm);

	$(document).off('submit', blockTujuanForm + '.form-tujuan');
	$(document).on('submit', blockTujuanForm + '.form-tujuan', function(e) {
		e.preventDefault();
		$.post('/rpjmd/tujuan_save/<?php echo $act; ?>', $(blockTujuanForm + '.form-tujuan').serializeArray(), function(res, status, xhr) {
			console.log(blockTujuanForm);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalTujuanForm.close();
				dataLoadTujuan();
			}
		});
		return false;
	});
});
</script>
