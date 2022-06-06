<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row entry-kegiatan-form">
	<div class="col-md-12">
		<form class="form-horizontal form-kegiatan-entry">
			<input type="hidden" id="i-pgrmrkpdkey" name="i-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
			<input type="hidden" id="i-kegrkpdkey" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
				<div class="form-group">
  				<label class="col-sm-2 control-label">Nomor Kegiatan</label>
  				<div class="col-sm-10">
  					<input type="text" name="i-nukeg" value="<?php echo $nukeg; ?>" class="form-control">
  				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Nama Kegiatan</label>
				<div class="col-sm-10">
					<textarea name="i-nmkeg" class="form-control" rows="5"><?php echo $nmkeg; ?></textarea>
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
var EntryKegiatanForm = '.entry-kegiatan-form ';

$(function() {

	$(document).off('submit', EntryKegiatanForm + '.form-kegiatan-entry');
	$(document).on('submit', EntryKegiatanForm + '.form-kegiatan-entry', function(e) {
		e.preventDefault();
		$.post('/master/kegiatan_save/<?php echo $act; ?>', $(EntryKegiatanForm + '.form-kegiatan-entry').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalMasterKegiatanForm.close();
				dataLoadMasterKegiatan();
			}
		});

		return false;
	});
});
</script>
