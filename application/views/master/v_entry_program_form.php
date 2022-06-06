<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row entry-program-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program-entry">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="i-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
			
			<div class="form-group">
			<label class="col-sm-2 control-label">Urusan Unit</label>
			<div class="col-sm-4">
			<select name="uruskey" id="uruskey" class="form-control input-large">
				<?php
					foreach($dataurusan as $r):
					$r = settrim($r); ?>
					<option value="<?php echo $r['URUSKEY']; ?>" <?php echo setselected($r['URUSAN'], $unitkey); ?>><?php echo $r['URUSAN']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			
			</div>
			  <div class="form-group">
				<label class="col-sm-2 control-label">No Program</label>
				<div class="col-sm-10">
				  <input type="text" name="i-nuprgrm" value="<?php echo $nuprgrm; ?>" class="form-control">
				</div>
			  </div>

			  <div class="form-group">
				<label class="col-sm-2 control-label">Program</label>
				<div class="col-sm-10">
				  <textarea name="i-nmprgrm" class="form-control" rows="5"><?php echo $nmprgrm; ?></textarea>

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
var EntryProgramForm = '.entry-program-form ';

$(function() {

	$(document).off('submit', EntryProgramForm + '.form-program-entry');
	$(document).on('submit', EntryProgramForm + '.form-program-entry', function(e) {
		e.preventDefault();
		$.post('/master/program_save/<?php echo $act; ?>', $(EntryProgramForm + '.form-program-entry').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalEntryProgramForm.close();
				dataLoadMasterProgram();
			}
		});

		return false;
	});
});
</script>
