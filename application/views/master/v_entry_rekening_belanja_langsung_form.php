<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-entry-rekening-belanja-langsung-form">
	<div class="col-md-12">
		<form class="form-horizontal form-entry-rekening-belanja-langsung">
				<input id="i-mtgkey" type="hidden" name="i-mtgkey" value="<?php echo $mtgkey; ?>">
				<input type="hidden" value="1" class="page">

							<div class="form-group">
								<label class="col-sm-2 control-label">Kode Rekening</label>
								<div class="col-sm-5">
										<div class="input-group">
											<input type="text" name="i-kdper" id="i-kdper" value="<?php echo $kdper; ?>" class="form-control text-bold">
										</div>
									</div>
								</div>

								<div class="form-group">
								<label class="col-sm-2 control-label">Uraian</label>
								<div class="col-sm-8">
										<input type="text" name="i-nmper" id="i-nmper" value="<?php echo $nmper; ?>"  class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">Level</label>
								<div class="col-sm-2">
                  	<input type="text" name="i-mtglevel" id="i-mtglevel" value="<?php echo $mtglevel; ?>"  class="form-control">

								</div>
							</div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Tipe</label>
                <div class="col-sm-10">
                  <div class="radio radio-inline">
                    <input type="radio" name="i-type" id="label-i-type-h" value="H" <?php echo setchecked('H', $type); ?>>
                    <label for="label-i-type-h">Header</label>
                  </div>

                  <div class="radio radio-inline">
                    <input type="radio" name="i-type" id="label-i-type-d" value="D" <?php echo setchecked('D', $type); ?>>
                    <label for="label-i-type-d">Detail</label>
                  </div>
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
var blockEntryRekeningBelanjaLangsungForm = '.block-entry-rekening-belanja-langsung-form ';

$(function() {
	updateMask(blockEntryRekeningBelanjaLangsungForm);

	$(document).off('submit', blockEntryRekeningBelanjaLangsungForm + '.form-entry-rekening-belanja-langsung');
	$(document).on('submit', blockEntryRekeningBelanjaLangsungForm + '.form-entry-rekening-belanja-langsung', function(e) {
		e.preventDefault();

		$.post('/master/MasterRekeningBelanjaLangsung_save/<?php echo $act; ?>', $(blockEntryRekeningBelanjaLangsungForm + '.form-entry-rekening-belanja-langsung').serializeArray(), function(res, status, xhr) {
			console.log(blockEntryRekeningBelanjaLangsungForm);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalRekeningBelanjaLangsungForm.close();
				dataLoadMasterRekeningBelanjaLangsung();
			}
		});

		return false;
	});

});
</script>
