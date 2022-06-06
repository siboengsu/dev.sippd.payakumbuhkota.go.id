<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-rincian-penjabaran-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-kegrkpdkey" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
			<input type="hidden" id="i-kdnilai" name="i-kdnilai" value="<?php echo $kdnilai; ?>">
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Kode</label>
				<div class="col-sm-10">
					<input type="text" name="i-kdjabar" class="form-control" value="<?php echo $kdjabar; ?>">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Uraian</label>
				<div class="col-sm-10">
					<textarea name="i-uraian" class="form-control" rows="5"><?php echo $uraian; ?></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Ekspresi</label>
				<div class="col-sm-3">
					<input type="text" name="i-ekspresi" value="<?php echo $ekspresi; ?>" class="form-control mask-nu2d">
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-2">
					<input type="text" name="i-satuan" value="<?php echo $satuan; ?>" class="form-control text-center" placeholder="Satuan">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Tarif</label>
				<div class="col-sm-5">
					<input type="text" name="i-tarif" value="<?php echo $tarif; ?>" class="form-control mask-nu2d">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator</label>
				<div class="col-sm-10">
					<select name="i-kddana" id="i-kddana" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" title="Pilih Sumber Dana">
						<?php foreach($list_jdana as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['KDDANA']; ?>" <?php echo setselected($r['KDDANA'], $kddana); ?>><?php echo $r['NMDANA']; ?></option>
						<?php endforeach; ?>
					</select>
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
var blockRincianPenjabaranForm = '.block-rincian-penjabaran-form ';

$(function() {
	updateMask(blockRincianPenjabaranForm);
	updateSelect(blockRincianPenjabaranForm);
	
	$(document).off('submit', blockRincianPenjabaranForm + '.form-program');
	$(document).on('submit', blockRincianPenjabaranForm + '.form-program', function(e) {
		e.preventDefault();
		$.post('/renja/rincian_penjabaran_save/<?php echo $act; ?>', $(blockRincianPenjabaranForm + '.form-program').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadRincian('penjabaran');
				modalRincianPenjabaranForm.close();
			}
		});
		
		return false;
	});
});
</script>

