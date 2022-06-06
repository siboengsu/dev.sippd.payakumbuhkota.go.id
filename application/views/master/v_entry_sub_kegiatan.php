<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row entry-sub-kegiatan-form">
	<div class="col-md-12">
		<form class="form-horizontal form-sub-kegiatan-entry">
			<input type="hidden" id="i-kegrkpdkey" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
			<input type="hidden" id="i-subkegrkpdkey" name="i-subkegrkpdkey" value="<?php echo $subkegrkpdkey; ?>">
				<div class="form-group">
  				<label class="col-sm-2 control-label">Nomor Kegiatan</label>
  				<div class="col-sm-10">
  					<input type="text" name="i-nusubkeg" value="<?php echo $nusubkeg; ?>" class="form-control">
  				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Nama Kegiatan</label>
				<div class="col-sm-10">
					<textarea name="i-nmsubkeg" class="form-control" rows="5"><?php echo $nmsubkeg; ?></textarea>
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
var EntrySubKegiatanForm = '.entry-sub-kegiatan-form ';

$(function() {
	$(document).off('submit', EntrySubKegiatanForm + '.form-sub-kegiatan-entry');
	$(document).on('submit', EntrySubKegiatanForm + '.form-sub-kegiatan-entry', function(e) {
		e.preventDefault();
		$.post('/master/subkegiatan_save/<?php echo $act; ?>', $(EntrySubKegiatanForm + '.form-sub-kegiatan-entry').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalMasterSubKegiatanForm.close();
				dataLoadMasterSubKegiatan();
			}
		});

		return false;
	});
});
</script>
