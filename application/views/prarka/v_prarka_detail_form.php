<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-detail-form">
	<div class="col-md-12">
		<form class="form-horizontal form-detail">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-kegrkpdkey" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
			<input type="hidden" id="i-mtgkey" name="i-mtgkey" value="<?php echo $mtgkey; ?>">
			<input type="hidden" id="i-kdjabar" name="i-kdjabar" value="<?php echo $kdjabar; ?>">
			<input type="hidden" id="i-kdper" name="i-kdper" value="<?php echo $kdper; ?>">
			<input type="hidden" id="i-kdnilai" name="i-kdnilai" value="<?php echo $kdnilai; ?>">

			<div class="form-group">
				<label class="col-sm-2 control-label">Kode</label>
				<div class="col-sm-5">
					<input type="text" name="i-kdjabar" value="<?php echo $kdjabar; ?>" class="form-control">
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

			<div class="form-group" style="display:none;" id="ssh">
				<label class="col-sm-2 control-label">Kode SSH/SB</label>
				<div class="col-sm-5">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-ssh"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" name="i-kdssh" id="i-kdssh" value="<?php echo $kdssh; ?>" class="form-control text-bold" readonly>
						
					</div>
				</div>
			</div>

			<div class="form-group" id="uraian">
				<label class="col-sm-2 control-label">Uraian</label>
				<div class="col-sm-10">
					<textarea name="i-uraian" id="i-uraian" class="form-control" rows="5" <?php echo $rossh;  ?>><?php echo $uraian; ?></textarea>
				</div>
			</div>

			<div class="form-group" id="jumlah" style="display:none;">
				<label class="col-sm-2 control-label">Volume</label>
				<div class="col-sm-3">
					<input type="text" name="i-jumbyek" value="<?php echo $jumbyek; ?>" class="form-control mask-nu2d">
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-2">
					<input type="text" name="i-satuan" id="i-satuan" value="<?php echo $satuan; ?>" class="form-control text-center" readonly placeholder="Satuan" <?php echo $rossh; ?>>
				</div>
			</div>

			<div class="form-group" id="tarif" style="display:none;">
				<label class="col-sm-2 control-label">Tarif</label>
				<div class="col-sm-5">
					<input type="text" name="i-tarif" id="i-tarif" value="<?php echo $tarif; ?>" class="form-control mask-nu2d"  readonly <?php echo $rossh; ?>>
				</div>
			</div>



			<div class="form-group" id="sumber" style="display:none;">
				<label class="col-sm-2 control-label">Sumber Dana</label>
				<div class="col-md-2">
					<select name="i-kddana" id="i-kddana" class="form-control  show-tick show-menu-arrow" data-width="auto" title="Pilih Sumber Dana">
						<?php
							foreach($sumberdana as $r):
								$r = settrim($r);
								?>
								<option value="<?php echo $r['KDDANA']; ?>" <?php echo setselected($r['KDDANA'], $kddana); ?>><?php echo $r['NMDANA']; ?></option>
								<?php endforeach; ?>
					</select>
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
var blockDetailForm = '.block-detail-form ';

$(function() {
	updateMask(blockDetailForm);
	
	window.onload=cekdata();

function cekdata(){
	 ctype = $('input[name=i-type]:checked').val();
	if (ctype == 'H'){
		$("#ssh").hide();
			$("#jumlah").hide();
				$("#tarif").hide();
					$("#sumber").hide();
					$('#i-uraian').prop('readonly', false);
	}else {
		$("#ssh").show()
		$("#jumlah").show();
			$("#tarif").show();
				$("#sumber").show();
				$('#i-uraian').prop('readonly', true);
	}
}

	$(document).off('click', blockDetailForm + '#label-i-type-h');
$(document).on('click', blockDetailForm + '#label-i-type-h', function(e) {

			$("#ssh").hide();
				$("#jumlah").hide();
					$("#tarif").hide();
						$("#sumber").hide();
						$('#i-uraian').prop('readonly', false);



});

$(document).off('click', blockDetailForm + '#label-i-type-d');
$(document).on('click', blockDetailForm + '#label-i-type-d', function(e) {
 ctype = $('input[name=i-type]:checked').val();


	$("#ssh").show()
	$("#jumlah").show();
		$("#tarif").show();
			$("#sumber").show();
			$('#i-uraian').prop('readonly', true);

});






	$(document).off('click', blockDetailForm + '.btn-lookup-ssh');
	$(document).on('click', blockDetailForm + '.btn-lookup-ssh', function(e) {
		e.preventDefault();
		var data = {
			'l-kdrek'	: getVal('#i-kdper'),
			'setkode'	: '#i-kdssh',
			'setnama'	: '#i-uraian',
			'setsatuan'	: '#i-satuan',
			'setharga'	: '#i-tarif'
		};

		modalLookupSsh = new BootstrapDialog({
			title: 'Lookup SSH / SB',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/ssh/', data)
		});
		modalLookupSsh.open();

		return false;
	});

	$(document).off('click', blockDetailForm + '.btn-remove-ssh');
	$(document).on('click', blockDetailForm + '.btn-remove-ssh', function(e) {

		$('#i-kdssh').val('');
		$('#i-uraian').val('').prop('readonly', false);
		$('#i-satuan').val('').prop('readonly', false);
		$('#i-tarif').val('0').prop('readonly', false);

		return false;
	});

	$(document).off('submit', blockDetailForm + '.form-detail');
	$(document).on('submit', blockDetailForm + '.form-detail', function(e) {
		e.preventDefault();
		$.post('/prarka/detail_save/<?php echo $act; ?>', $(blockDetailForm + '.form-detail').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadRekening(res);
				dataLoadDetail();
				modalDetailForm.close();
			}
		});

		return false;
	});
});
</script>
