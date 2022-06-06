<?php
defined('BASEPATH') OR exit('No direct script access allowed');
foreach($capaian_program as $c):
$c = settrim($c);
$capaian = $c['TOLOKUR'];
$sasaran = $c['TARGET'];
endforeach;
foreach($target_subkegiatan as $tk):
$tk = settrim($tk);
$tgSUB = $tk['TARGET'];
$tgket = $tk['KET'];
$tgSubKegN = str_replace(".0000", "", $tk['PAGUTIF']);
endforeach;
?>

<div class="row block-sub-rincian-kinerja-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-kegrkpdkey" name="i-subkegrkpdkey" value="<?php echo $subkegrkpdkey; ?>">
			<textarea id="capaian" name="capaian" style="display:none;"><?php echo $capaian; ?></textarea>
			<textarea id="sasaran" name="sasaran" style="display:none;"><?php echo $sasaran; ?></textarea>
			<textarea id="tarSUB" name="tarSUB" style="display:none;"><?php echo $tgSUB; ?></textarea>
			<textarea id="tarket" name="tarket" style="display:none;"><?php echo $tgket; ?></textarea>
			<textarea id="tgSubKegN" name="tgSubKegN" style="display:none;"><?php echo $tgSubKegN; ?></textarea>
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
						?>
						<option value="<?php echo $r['KDJKK']; ?>" <?php echo setselected($r['KDJKK'], $kdjkk); ?>><?php echo $r['URJKK']; ?></option>
						<?php } ?>
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
					<textarea name="i-targetmin1" id="i-targetmin1" class="form-control" rows="5"><?php echo $targetmin1; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Target Kinerja n</label>
				<div class="col-sm-10">
					<textarea name="i-target" id="i-target" class="form-control" rows="5" <?php if ($cr=="00" or $cr == "01" or $cr == "02") {echo "readonly";} ?>><?php echo $target; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Target Kinerja (n+1)</label>
				<div class="col-sm-10">
					<textarea name="i-target1" id="i-target1" class="form-control" rows="5"><?php echo $target1; ?></textarea>
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
var blockSubRincianKinerjaForm = '.block-sub-rincian-kinerja-form ';

$(function() {
	updateSelect(blockSubRincianKinerjaForm);
	$(document).off('submit', blockSubRincianKinerjaForm + '.form-program');
	$(document).on('submit', blockSubRincianKinerjaForm + '.form-program', function(e) {
		e.preventDefault();
		$.post('/renja/subrincian_kinerja_save/<?php echo $act; ?>', $(blockSubRincianKinerjaForm + '.form-program').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadRincian('kinerja');
				dataLoadRincian('kinerjasub');
				modalSubRincianKinerjaForm.close();
			}
		});
		return false;
	});

	$(document).off('change', blockSubRincianKinerjaForm + '#i-kdjkk');
	$(document).on('change', blockSubRincianKinerjaForm + '#i-kdjkk', function(e) {
		e.preventDefault();
		var kdjkk = getVal('#i-kdjkk');
		if (kdjkk=="00"){
			var capaian = document.getElementById("capaian").value;
			var sasaran = document.getElementById("sasaran").value;
			document.getElementById("i-tolokur").innerHTML = capaian ;
		 	document.getElementById("i-target").innerHTML = sasaran ;
			$('#i-tolokur').prop('readonly', true);
			$('#i-target').prop('readonly', true);
		}
		else if (kdjkk=="01"){
			var tgSubKegN = document.getElementById("tgSubKegN").value;
			document.getElementById("i-target").innerHTML = tgSubKegN ;
			$('#i-target').prop('readonly', true);

			$('#i-tolokur').prop('readonly', false);
			document.getElementById("i-tolokur").innerHTML = "" ;
		}
		else if (kdjkk=="02"){
			var tar = document.getElementById("tarSUB").value;
			var tarket = document.getElementById("tarket").value;
			document.getElementById("i-target").innerHTML = tar ;
			document.getElementById("i-tolokur").innerHTML = tarket ;
			$('#i-target').prop('readonly', true);
			$('#i-tolokur').prop('readonly', true);
		}
		else {
			$('#i-tolokur').prop('readonly', false);
			$('#i-target').prop('readonly', false);
			document.getElementById("i-tolokur").innerHTML = "" ;
			document.getElementById("i-target").innerHTML = "" ;
		}
	});
});

</script>
