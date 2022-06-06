<?php
defined('BASEPATH') OR exit('No direct script access allowed');
foreach($capaian_program as $c):
$c = settrim($c);
$capaian = $c['TOLOKUR'];
$sasaran = $c['TARGET'];
endforeach;

foreach($target_kegiatan as $tk):
$tk = settrim($tk);
$tg = $tk['KUANTITATIF'];
$tgket = $tk['KET'];
endforeach;

foreach($masukan_target_kegiatan as $mtk):
$mtk = settrim($mtk);
$target_masukan = $mtk['TARGET'];
$target_masukan1 = $mtk['TARGET1'];
$target_masukanmin1 = $mtk['TARGETMIN1'];
endforeach;



?>
<div class="row block-rincian-kinerja-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-kegrkpdkey" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
			<textarea id="capaian" name="capaian" style="display:none;"><?php echo $capaian; ?></textarea>
			<textarea id="sasaran" name="sasaran" style="display:none;"><?php echo $sasaran; ?></textarea>
			<textarea id="tar" name="tar" style="display:none;"><?php echo $tg; ?></textarea>
			<textarea id="tarket" name="tarket" style="display:none;"><?php echo $tgket; ?></textarea>
			<textarea id="target_masukan" name="target_masukan" style="display:none;"><?php echo $target_masukan; ?></textarea>
			<textarea id="target_masukan1" name="target_masukan1" style="display:none;"><?php echo $target_masukan1; ?></textarea>
			<textarea id="target_masukanmin1" name="target_masukanmin1" style="display:none;"><?php echo $target_masukanmin1; ?></textarea>
			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator</label>
				<div class="col-sm-10">
					<select name="i-kdjkk" id="i-kdjkk" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" title="Pilih Indikator">
						<?php
						foreach($list_jkinkeg as $r):
						$r = settrim($r);
						$cr=  $r['KDJKK'];
						if($cr == "00" || $cr == "01" || $cr == "02" || $cr == "04" || $cr == "11")
						{
							if($cr == "02")  
							{
								$r['URJKK'] = 'Hasil';
							}
							if($cr == "03")  
							{
								$r['URJKK'] = 'Keluaran';
							}
						 ?>

						<option value="<?php echo $r['KDJKK']; ?>" <?php echo setselected($r['KDJKK'], $kdjkk); ?>><?php echo $r['URJKK']; ?></option>
						
						<?php }?>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Tolak Ukur</label>
				<div class="col-sm-10">
					<textarea name="i-tolokur" id="i-tolokur" class="form-control" rows="5" <?php if ($cr=="00" || $cr=="02") {echo "readonly";} ?>><?php echo $tolokur; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Target Kinerja <br>(n-1)</label>
				<div class="col-sm-10">
					<textarea name="i-targetmin1" id="i-targetmin1" class="form-control" rows="5" <?php if ($cr=="01" )  {echo "readonly";} ?>><?php echo $targetmin1; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Target Kinerja n</label>
				<div class="col-sm-10">
					<textarea name="i-target" id="i-target" class="form-control" rows="5" <?php if ($cr=="00" || $cr=="01" || $cr=="02")  {echo "readonly";} ?>><?php echo $target; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Target Kinerja (n+1)</label>
				<div class="col-sm-10">
					<textarea name="i-target1"  id="i-target1"  class="form-control" rows="5" <?php if ($cr=="01" )  {echo "readonly";} ?>><?php echo $target1; ?></textarea>
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
var blockRincianKinerjaForm = '.block-rincian-kinerja-form ';

$(function() {
	updateSelect(blockRincianKinerjaForm);

	$(document).off('submit', blockRincianKinerjaForm + '.form-program');
	$(document).on('submit', blockRincianKinerjaForm + '.form-program', function(e) {
		e.preventDefault();
		$.post('/renja/rincian_kinerja_save/<?php echo $act; ?>', $(blockRincianKinerjaForm + '.form-program').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadRincian('kinerja');
				modalRincianKinerjaForm.close();
			}
		});

		return false;
	});

	$(document).off('change', blockRincianKinerjaForm + '#i-kdjkk');
	$(document).on('change', blockRincianKinerjaForm + '#i-kdjkk', function(e) {
		e.preventDefault();
		var kdjkk = getVal('#i-kdjkk');
		if (kdjkk=="00"){
			var capaian = document.getElementById("capaian").value;
			var sasaran = document.getElementById("sasaran").value;

			document.getElementById("i-tolokur").innerHTML = capaian ;
		 	document.getElementById("i-target").innerHTML = sasaran ;
			$('#i-tolokur').prop('readonly', true);
			$('#i-target').prop('readonly', true);

			$('#i-target1').prop('readonly', false);
			$('#i-targetmin1').prop('readonly', false);
			document.getElementById("i-target1").innerHTML = "" ;
			document.getElementById("i-targetmin1").innerHTML = "" ;
		}
		else if (kdjkk=="02"){
			var tar = document.getElementById("tar").value;
			var tarket = document.getElementById("tarket").value;
			document.getElementById("i-target").innerHTML = tar ;
			document.getElementById("i-tolokur").innerHTML = tarket;
			$('#i-target').prop('readonly', true);
			$('#i-tolokur').prop('readonly', true);

			$('#i-target1').prop('readonly', false);
			$('#i-targetmin1').prop('readonly', false);
			document.getElementById("i-target1").innerHTML = "" ;
			document.getElementById("i-targetmin1").innerHTML = "" ;
		}
		else if (kdjkk=="01"){
			var target_masukan = document.getElementById("target_masukan").value;
			var target_masukan1 = document.getElementById("target_masukan1").value;
			var target_masukanmin1 = document.getElementById("target_masukanmin1").value;
			document.getElementById("i-target").innerHTML = target_masukan ;
			document.getElementById("i-targetmin1").innerHTML = target_masukanmin1 ;
			document.getElementById("i-target1").innerHTML = target_masukan1 ;
			$('#i-target').prop('readonly', true);
			$('#i-target1').prop('readonly', true);
			$('#i-targetmin1').prop('readonly', true);

			$('#i-tolokur').prop('readonly', false);
			document.getElementById("i-tolokur").innerHTML = "" ;
		}
		else {
			$('#i-tolokur').prop('readonly', false);
			$('#i-target').prop('readonly', false);
			$('#i-target1').prop('readonly', false);
			$('#i-targetmin1').prop('readonly', false);
			document.getElementById("i-tolokur").innerHTML = "" ;
			document.getElementById("i-target").innerHTML = "" ;
			document.getElementById("i-target1").innerHTML = "" ;
			document.getElementById("i-targetmin1").innerHTML = "" ;
		}
	});
});
</script>
