<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-sub-rincian-sumberdana-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-subkegrkpdkey" name="i-subkegrkpdkey" value="<?php echo $subkegrkpdkey; ?>">

			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator</label>
				<div class="col-sm-10">
					<select name="i-kddana" id="i-kddana" class="form-control selectpicker show-tick show-menu-arrow"  title="Pilih Sumber Dana">
						<?php foreach($list_jdana_sub as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['KDDANA']; ?>" <?php echo setselected($r['KDDANA'], $kddana); ?>><?php echo $r['NMDANA']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Nilai n</label>
				<div class="col-sm-5">
					<input type="text" name="i-nilai" value="<?php echo $nilai; ?>" class="form-control mask-nu2d">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Nilai n+1</label>
				<div class="col-sm-5">
					<input type="text" name="i-nilai1" value="<?php echo $nilai1; ?>" class="form-control mask-nu2d">
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
var blockSubRincianSumberdanaForm = '.block-sub-rincian-sumberdana-form ';

$(function() {
	 //$('#i-kddana').selectpicker('refresh');
	updateMask(blockSubRincianSumberdanaForm);
	updateSelect(blockSubRincianSumberdanaForm);

	$(document).off('submit', blockSubRincianSumberdanaForm + '.form-program');
	$(document).on('submit', blockSubRincianSumberdanaForm + '.form-program', function(e) {
		e.preventDefault();
		$.post('/renja/subrincian_sumberdana_save/<?php echo $act; ?>', $(blockSubRincianSumberdanaForm + '.form-program').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadRincian('sumberdanasub');
				modalSubRincianSumberdanaForm.close();
			}
		});

		return false;
	});
});
</script>
