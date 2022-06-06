<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-pagu-form"  <?php if ($act == "edit"){echo "style= display:none;" ;} ?>>
	<div class="col-md-12">
		<form class="form-horizontal form-pagu-entry">
				<input type="hidden" name="f-unitkey" id="f-unitkey" value="<?php echo $this->session->UNITKEY; ?>" required>
				<input type="hidden" value="1" class="page">

					<div class="form-gap visible-xs-block"></div>
						<div class="form-group">
								<label class="col-sm-2 control-label">Nama Unit</label>
									<div class="col-sm-10">
											<?php if($this->sip->is_admin()): ?>

												<div class="input-group">
													<span class="input-group-btn">
														<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
													</span>

													<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>

												</div>
												<?php else: ?>
												<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
												<?php endif; ?>
									</div>
						</div>

						<div class="form-group">
								<label class="col-sm-2 control-label">Kode Unit</label>
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
										<input type="text" value="<?php echo $this->session->KDUNIT; ?>" id="v-kdunit" class="form-control text-bold" placeholder="Kode Unit" readonly>
									</div>
								</div>
							</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Pagu</label>
							<div class="col-sm-4">
								<input type="text" name="v-pagu" value="<?php echo $nilai ?>" class="form-control mask-nu2d">
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

<div class="row block-pagu-form"  <?php if ($act == "add"){echo "style= display:none;" ;} ?>>
	<div class="col-md-12">
		<form class="form-horizontal form-pagu-entry">
				<input type="hidden" name="f-unitkey" id="f-unitkey" value="<?php echo $unitkey; ?>" required>
				<input type="hidden" value="1" class="page">

					<div class="form-gap">
						<div class="form-group">
								<label class="col-sm-2 control-label">Nama Unit</label>
								<div class="col-sm-10">
										<?php if($this->sip->is_admin()): ?>
												<div class="input-group">
													<span class="input-group-btn">
														<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" <?php echo $disabled; ?> data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
													</span>
													<input type="text" id="v-nmunit" value="<?php echo $NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
												</div>
										<?php else: ?>
												<input type="text" id="v-nmunit" value="<?php echo $NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
										<?php endif; ?>
								</div>
						</div>

						<div class="form-group">
								<label class="col-sm-2 control-label">Kode Unit</label>
								<div class="col-sm-4">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
										<input type="text" value="<?php echo $KDUNIT; ?>" id="v-kdunit" class="form-control text-bold" placeholder="Kode Unit" readonly>
									</div>
								</div>
							</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Pagu</label>
							<div class="col-sm-4">
								<input type="text" name="v-pagu" value="<?php echo $nilai ?>" class="form-control mask-nu2d">
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
var blockPaguForm = '.block-pagu-form ';

$(function() {
	updateMask(blockPaguForm);

	$(document).off('submit', blockPaguForm + '.form-pagu-entry');
	$(document).on('submit', blockPaguForm + '.form-pagu-entry', function(e) {
		e.preventDefault();
		 var id = $('#f-unitkey').val();
		$.post('/master/pagu_save/<?php echo $act; ?>/'+id, $(blockPaguForm + '.form-pagu-entry').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalEntryPaguForm.close();
				dataLoadMasterPagu();
			}
		});

		return false;
	});


});
</script>
