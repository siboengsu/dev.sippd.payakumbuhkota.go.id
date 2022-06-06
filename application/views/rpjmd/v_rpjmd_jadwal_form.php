<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-jadwal-form">
	<div class="col-md-12">
		<form class="form-horizontal form-jadwal">
			<input id="i-idjadwal" type="hidden" name="i-idjadwal" value="<?php echo $idjadwal; ?>">
			<input type="hidden" value="1" class="page">
			<div class="form-group">
				<label class="col-sm-2 control-label">Periode</label>
				<div class="col-sm-2">
					<input name="i-periode_awal" value="<?php echo $periode_awal; ?>" class="form-control" rows="5"></input>
				</div>
				<label class="col-sm-1 control-label" style="text-align:center">s/d</label>
				<div class="col-sm-2">
					<input name="i-periode_akhir" value="<?php echo $periode_akhir; ?>" class="form-control" rows="5" value=""></input>
				</div>
 			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Tahapan</label>
				<div class="col-sm-10">
					<select name="i-tahapan" id="i-tahapan" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" title="Pilih Tahapan">
						<?php
						foreach($tahapan as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['ID']; ?>" <?php echo setselected($r['ID'], $idtahapan); ?>><?php echo $r['TAHAPAN']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
            <div class="form-group">
				<label class="col-sm-2 control-label">Sub Tahapan</label>
				<div class="col-sm-10">
					<select name="i-subtahap" id="i-subtahap" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" title="Pilih Sub Tahapan">
						<?php
						foreach($subtahap as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['ID']; ?>" <?php echo setselected($r['ID'], $idsubtahapan); ?>><?php echo $r['SUBTAHAPAN']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
            <div class="form-group">
				<label class="col-sm-2 control-label">Keterangan</label>
				<div class="col-sm-9">
					<input name="i-ket" value="<?php echo $ket; ?>" class="form-control" rows="5"></input>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Pelaksanaan</label>
				<div class="col-sm-4">
					<div class="input-group flatpickr">
						<input type="text" name="i-jadwal_awal" id="i-jadwal_awal" value="<?php echo $jadwal_awal; ?>" class="form-control text-center" placeholder="Pilih Tanggal" data-input>
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" data-toggle><i class="fa fa-calendar"></i></button>
							<button type="button" class="btn btn-default" data-clear><i class="fa fa-times"></i></button>
						</div>
					</div>
				</div>
				<label class="col-sm-1 control-label" style="text-align:center">s/d</label>
				<div class="col-sm-4">
					<div class="input-group flatpickr">
						<input type="text" name="i-jadwal_akhir" id="i-jadwal_akhir" value="<?php echo $jadwal_akhir; ?>" class="form-control text-center" placeholder="Pilih Tanggal" data-input>
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" data-toggle><i class="fa fa-calendar"></i></button>
							<button type="button" class="btn btn-default" data-clear><i class="fa fa-times"></i></button>
						</div>
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
var blockJadwalForm = '.block-jadwal-form ';
$(function() {
	updateMask(blockJadwalForm);
	updateSelect(blockJadwalForm);
	updateDate(blockJadwalForm);

	$(document).off('submit', blockJadwalForm + '.form-jadwal');
	$(document).on('submit', blockJadwalForm + '.form-jadwal', function(e) {
		e.preventDefault();
		$.post('/rpjmd/jadwal_save/<?php echo $act; ?>', $(blockJadwalForm + '.form-jadwal').serializeArray(), function(res, status, xhr) {
			console.log(blockJadwalForm);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalJadwalForm.close();
				dataLoadJadwal();
			}
		});
		return false;
	});
});
</script>
