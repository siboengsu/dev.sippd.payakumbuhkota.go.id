<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-rincian-hspk-form">
	<div class="col-md-12">
		<form class="form-horizontal form-program">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-kegrkpdkey" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
			<input type="hidden" id="i-id_keghspk" name="i-id_keghspk" value="<?php echo $id_keghspk; ?>">
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Kode HSPK</label>
				<div class="col-sm-5">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-hspk" <?php echo $dsbtnhspk; ?>><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" name="i-kdhspk3" id="i-kdhspk3" value="<?php echo $kdhspk3; ?>" class="form-control text-bold" readonly>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-remove-hspk" <?php echo $dsbtnhspkremove; ?>><i class="fa fa-times"></i></button>
						</span>
					</div>
				</div>
				<div class="col-sm-1">
					<button type="button" class="btn btn-md btn-info btn-hspk" id="v-kdhspk3" data-id="<?php echo $kdhspk3; ?>"><i class="fa fa-balance-scale"></i> HSPK</button>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Nama Pekerjaan</label>
				<div class="col-sm-10">
					<input type="text" name="i-nmpekerjaan" id="i-nmpekerjaan" value="<?php echo $nmpekerjaan; ?>" class="form-control" <?php echo $rohspk; ?>>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Jenis Pekerjaan</label>
				<div class="col-sm-10">
					<input type="text" name="i-jnpekerjaan" id="i-jnpekerjaan" value="<?php echo $jnpekerjaan; ?>" class="form-control" <?php echo $rohspk; ?>>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Volume</label>
				<div class="col-sm-3">
					<input type="text" name="i-volume" value="<?php echo $volume; ?>" class="form-control mask-nu2d" <?php echo $rovolume; ?>>
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-2">
					<input type="text" name="i-satuan" id="i-satuan" value="<?php echo $satuan; ?>" class="form-control text-center" <?php echo $rohspk; ?> placeholder="Satuan">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Harga Satuan</label>
				<div class="col-sm-5">
					<input type="text" name="i-harga" id="i-harga" value="<?php echo $harga; ?>" class="form-control mask-nu2d" <?php echo $rohspk; ?>>
				</div>
			</div>
			
			<?php if($tipe != '1'): ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Kecamatan</label>
				<div class="col-sm-10">
					<input type="text" name="i-kecamatan" value="<?php echo $kecamatan; ?>" class="form-control" <?php echo $rokecamatan; ?>>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Kelurahan</label>
				<div class="col-sm-10">
					<input type="text" name="i-kelurahan" value="<?php echo $kelurahan; ?>" class="form-control" <?php echo $rokelurahan; ?>>
				</div>
			</div>
			<?php endif; ?>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Lokasi</label>
				<div class="col-sm-10">
					<textarea name="i-lokasi" class="form-control" rows="5" <?php echo $rolokasi; ?>><?php echo $lokasi; ?></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Keterangan</label>
				<div class="col-sm-10">
					<textarea name="i-keterangan" class="form-control" rows="5" <?php echo $roketerangan; ?>><?php echo $keterangan; ?></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success <?php echo $curdShow; ?>"><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>
		</form>
	</div>
	
	<?php if($tipe != '1' AND $act == 'edit'): ?>
	<div class="col-md-12">
		<hr>
		<div class="table-responsive block-pagu">
		<table class="table table-condensed table-bordered">
		<tbody>
		<tr>
			<td class="w1px text-bold text-nowrap">Kode HSPK</td>
			<td class="w1px">:</td>
			<td><button type="button" class="btn btn-xs btn-info btn-hspk" id="v-kdhspk3" data-id="<?php echo $ref_kdhspk3; ?>"><i class="fa fa-balance-scale"></i> <?php echo $ref_kdhspk3; ?></button></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Nama Pekerjaan</td>
			<td class="w1px">:</td>
			<td><?php echo $ref_nmpekerjaan; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Jenis Pekerjaan</td>
			<td class="w1px">:</td>
			<td><?php echo $ref_jnpekerjaan; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Kecamatan</td>
			<td class="w1px">:</td>
			<td><?php echo $ref_kecamatan; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Kelurahan</td>
			<td class="w1px">:</td>
			<td><?php echo $ref_kelurahan; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Lokasi</td>
			<td class="w1px">:</td>
			<td><?php echo $ref_lokasi; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Keterangan</td>
			<td class="w1px">:</td>
			<td><?php echo $ref_keterangan; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Satuan</td>
			<td class="w1px">:</td>
			<td><?php echo $ref_satuan; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Volume</td>
			<td class="w1px">:</td>
			<td class="text-right text-bold nu2d"><?php echo $ref_volume; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Harga Satuan</td>
			<td class="w1px">:</td>
			<td class="text-right text-bold nu2d"><?php echo $ref_harga; ?></td>
		</tr>
		<tr>
			<td class="w1px text-bold text-nowrap">Total</td>
			<td class="w1px">:</td>
			<td class="text-right text-bold nu2d"><?php echo $ref_total; ?></td>
		</tr>
		</tbody>
		</table>
		</div>
	</div>
	<?php endif; ?>
</div>
<script>
var blockRincianHspkForm = '.block-rincian-hspk-form ';

$(function() {
	updateNum(blockRincianHspkForm);
	updateMask(blockRincianHspkForm);
	
	$(document).off('click', blockRincianHspkForm + '.btn-lookup-hspk');
	$(document).on('click', blockRincianHspkForm + '.btn-lookup-hspk', function(e) {
		e.preventDefault();
		var data = {
			'setkode'	: '#i-kdhspk3',
			'setifno'	: '#v-kdhspk3',
			'setnmpek'	: '#i-nmpekerjaan',
			'setjnpek'	: '#i-jnpekerjaan',
			'setsatuan'	: '#i-satuan',
			'setharga'	: '#i-harga'
		};
		
		modalLookupHspk2 = new BootstrapDialog({
			title: 'Lookup HSPK (Nama Pekerjaan)',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/hspk2/', data)
		});
		modalLookupHspk2.open();
		
		return false;
	});
	
	$(document).off('click', blockRincianHspkForm + '.btn-remove-hspk');
	$(document).on('click', blockRincianHspkForm + '.btn-remove-hspk', function(e) {
		
		$('#i-kdhspk3').val('');
		$('#v-kdhspk3').data('id', '');
		$('#i-nmpekerjaan').val('').prop('readonly', false);
		$('#i-jnpekerjaan').val('').prop('readonly', false);
		$('#i-satuan').val('').prop('readonly', false);
		$('#i-harga').val('').prop('readonly', false);
			
		return false;
	});
	
	$(document).off('submit', blockRincianHspkForm + '.form-program');
	$(document).on('submit', blockRincianHspkForm + '.form-program', function(e) {
		e.preventDefault();
		var tipe = '<?php echo $tipe; ?>';
		$.post('/renja/rincian_hspk_save/' + tipe + '/<?php echo $act; ?>', $(blockRincianHspkForm + '.form-program').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadKegiatan(res);
				if(tipe == '1') dataLoadRincian('opd');
				if(tipe == '2') dataLoadRincian('musrenbang');
				if(tipe == '3') dataLoadRincian('pokir');
				modalRincianHspkForm.close();
				
			}
		});
		
		return false;
	});
});
</script>

