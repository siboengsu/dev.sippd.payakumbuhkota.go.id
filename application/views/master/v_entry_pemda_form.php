<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-pemda-form">
	<div class="col-md-12">
		<form class="form-horizontal form-pemda-entry">
				<input name="f-configid" id="f-configid" value="<?php echo $configid; ?>" required>
				<input type="hidden" value="1" class="page">

        <div class="form-group">
          <label class="col-sm-2 control-label">URAIAN</label>
          <div class="col-sm-4">
            <input type="text" name="v-configdes" value="<?php echo $configdes ?>" class="form-control">
          </div>
        </div>

						<div class="form-group">
							<label class="col-sm-2 control-label">NILAI</label>
							<div class="col-sm-4">
								<input type="text" name="v-configval" value="<?php echo $configval ?>" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-success <?php echo $curdShow; ?>"><i class="fa fa-download"></i> Simpan</button>
							</div>
						</div>
					</div>
			</form>
	</div>
</div>

<script>
var blockPemdaForm = '.block-pemda-form ';

$(function() {

	$(document).off('submit', blockPemdaForm + '.form-pemda-entry');
	$(document).on('submit', blockPemdaForm + '.form-pemda-entry', function(e) {
		e.preventDefault();
     var id = $('#f-configid').val();
		$.post('/master/pemda_save/<?php echo $act; ?>/'+id, $(blockPemdaForm + '.form-pemda-entry').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalEntryPemdaForm.close();
				dataLoadMasterPemda();
			}
		});

		return false;
	});


});
</script>
