<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-opd-form">
	<div class="col-md-12">
		<form class="form-horizontal form-opd">
			<input type="hidden" id="i-id" name="i-id" value="<?php echo $id;?>">
			<input type="hidden" value="1" class="page">
            <div class="form-group" id="prktdaerah">
				<label class="col-sm-2 control-label">OPD</label>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
						<input type="text" value="<?php echo $unitkey; ?>" id="f-unitkey" name="f-unitkey" class="form-control text-bold" placeholder="Kode Unit" readonly>
					</div>
				</div>

				<div class="form-gap visible-xs-block"></div>
				<?php 
				if($act == 'edit'){
					if ($unitkey != "")
					{
						$row = $this->db->query("SELECT NMUNIT FROM DAFTUNIT WHERE UNITKEY = '$unitkey'")->row_array();
						foreach ($row as $r)
						{
							$opd = $r;
						}	
					}
				}
				?>
				<div class="col-sm-7">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-unit-uptd-blud" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmunit" name="v-nmunit" value="<?php echo $opd; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Pegawai</label>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
						<input type="text" value="<?php echo $nip; ?>" id="f-nip" name="f-nip" class="form-control text-bold" placeholder="Nama Group" readonly>
					</div>
				</div>

				<div class="form-gap visible-xs-block"></div>
				
				<div class="col-sm-7">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-pegawai" data-setid="#f-nip" data-setnm="#v-nama"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nama" value="<?php echo $nama; ?>" class="form-control text-bold v-nama" placeholder="Nama Group" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">Jabatan</label>
				<div class="col-sm-9">
					<select name="i-jabatan" id="i-jabatan" class="form-control" data-width="auto" title="Pilih Tahap">
						<option value=""  style="display: none;"><?php echo $kdjabatan;?></option>
						<?php foreach($jabatan as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['KDJABATAN']; ?>" ><?php echo $r['JABATAN']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group custom-control-inline">
				<label for="tglcetak" class="col-sm-2 control-label">Masa Jabatan</label>
				<div class="col-sm-3">
					<div class="input-group flatpickr">
						<input type="text" name="f-tglawal" id="f-tglawal" value="<?php if ($act == 'add'){echo date("Y-m-d");}else{echo $periode1;} ?>" class="form-control text-center" data-input>
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" aria-label="Bold" data-toggle>
								<i class="fa fa-calendar"></i>
							</button>
						</div>
					</div>
				</div>
				<label class="col-xs-1 control-label custom-control-inline">sampai</label>
				<div class="col-sm-3">
					<div class="input-group flatpickr">
						<input type="text" name="f-tglakhir" id="f-tglakhir" value="<?php if ($act == 'add'){echo date("Y-m-d");}else{echo $periode2;} ?>" class="form-control text-center" data-input>
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" aria-label="Bold" data-toggle>
								<i class="fa fa-calendar"></i>
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success "><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>			
		</form>
	</div>
</div>
<script>
var blockopdform = '.block-opd-form ';
$(function() {
	updateDateP();

	updateMask(blockopdform);

	$(document).off('click', blockopdform + '.btn-lookup-pegawai');
	$(document).on('click', blockopdform + '.btn-lookup-pegawai', function(e) {
		e.preventDefault();
		var data = {
			'setid'	: '#f-nip',
			'setnm'	: '.v-nama'
		};

		modalLookupPegawai = new BootstrapDialog({
			title: 'Lookup Pegawai',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/opd/pegawaiindex/', data)
		});
		modalLookupPegawai.open();

		return false;
	});

	$(document).off('submit', blockopdform + '.form-opd');
	$(document).on('submit', blockopdform + '.form-opd', function(e) {
		e.preventDefault();
		$.post('/opd/opd_save/<?php echo $act; ?>', $(blockopdform + '.form-opd').serializeArray(), function(res, status, xhr) {
			console.log(blockopdform);
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalopdform.close();
				dataLoadOpd();
			}
		});
		return false;
	});

	$(document).off('change', '#f-groupid');
	$(document).on('change', '#f-groupid', function(e) {
		e.preventDefault();
		dataLoad();
	});
	
	$(document).off('submit', blockopdform + '.form-load');
	$(document).on('submit', blockopdform + '.form-load', function(e) {
		e.preventDefault();
		dataLoad();
		return false;
	});
	
	$(document).off('click', blockopdform + '.btn-lookup-unit-uptd-blud');
	$(document).on('click', blockopdform + '.btn-lookup-unit-uptd-blud', function(e) {
		e.preventDefault();
		var data = {
			'setid'	: '#f-unitkey',
			'setnm'	: '#v-nmunit'
		};
		
		modalLookupUnitUptdBlud = new BootstrapDialog({
			title: 'Lookup Group',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/unit_uptd_blud/', data)
		});
		modalLookupUnitUptdBlud.open();
		return false;

	});
});
</script>
