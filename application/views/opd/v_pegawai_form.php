<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-pegawai-form">
	<div class="col-md-12">
		<form class="form-horizontal form-pegawai">
			<input type="hidden" id="i-nip" name="i-nip" value="<?php echo $nip;?>">
			<input type="hidden" value="1" class="page">

            <div class="form-group">
				<label class="col-sm-2 control-label">NIP</label>
				<div class="col-sm-9">
					<input type="text" name="i-nip" id="i-nip" class="form-control" value="<?php echo $nip; ?>">
				</div>
			</div>
			
			<div class="form-group" id="nmpengguna"> 
				<label class="col-sm-2 control-label">Nama</label>
				<div class="col-sm-9">
					<input type="text" name="i-nama" id="i-nama" class="form-control" value="<?php echo $nama; ?>">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">Golongan</label>
				<div class="col-sm-9">
					<select name="i-kdgol" id="i-kdgol" class="form-control" data-width="auto" title="Pilih Tahap">
						<option value=""  style="display: none;"><?php echo $kdgol;?></option>
						<?php foreach($golongan as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['KDGOL']; ?>" ><?php echo $r['KDGOL']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Jabatan</label>
				<div class="col-sm-9">
					<input type="text" name="i-jabatan" id="i-jabatan" class="form-control" value="<?php echo $jabatan; ?>">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
$('#i-nip').inputmask("99999999 999999 9 999", {"placeholder": ""})

var blockpegawaiform = '.block-pegawai-form ';
$(function() {
	updateMask(blockpegawaiform);

	$(document).off('submit', blockpegawaiform + '.form-pegawai');
	$(document).on('submit', blockpegawaiform + '.form-pegawai', function(e) {
		e.preventDefault();
		$.post('/opd/pegawai_save/<?php echo $act; ?>', $(blockpegawaiform + '.form-pegawai').serializeArray(), function(res, status, xhr) {
			console.log(blockpegawaiform);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalpegawaiform.close();
				dataLoadPegawai();
			}
		});
		return false;
	});

	$(document).off('change', '#f-groupid');
	$(document).on('change', '#f-groupid', function(e) {
		e.preventDefault();
		dataLoad();
	});
	
	$(document).off('submit', blockpegawaiform + '.form-load');
	$(document).on('submit', blockpegawaiform + '.form-load', function(e) {
		e.preventDefault();
		dataLoad();
		return false;
	});
	
	$(document).off('click', blockpegawaiform + '.btn-lookup-group');
	$(document).on('click', blockpegawaiform + '.btn-lookup-group', function(e) {
		e.preventDefault();
		var data = {
			'setid'	: '#f-groupid',
			'setnm'	: '.v-nmgroup'
		};
		
		modalLookupGroup = new BootstrapDialog({
			title: 'Lookup Group',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/group/', data)
		});
		modalLookupGroup.open();
		return false;

	});
});
</script>
