<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$kdtahap = $this->KDTAHAP = $this->session->KDTAHAP;
?>
<div class="row block-subkegiatan-form">
	<div class="col-md-12">
		<form class="form-horizontal form-subkegiatan">
			<input type="hidden" id="v-unitkey" name="v-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="v-kegrkpdkey" name="v-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
			<input type="hidden" id="v-subkegrkpdkey" name="v-subkegrkpdkey" value="<?php echo $subkegrkpdkey; ?>">

			<div class="form-group">
				<label class="col-sm-2 control-label">Sub Kegiatan</label>
				<div class="col-sm-2">
					<input type="text" id="v-nusubkeg" value="<?php echo $nusubkeg; ?>" class="form-control text-bold" readonly>
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-subkegiatan"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmsubkeg" value="<?php echo $nmsubkeg; ?>" class="form-control text-bold" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Sifat Sub Kegiatan</label>
				<div class="col-sm-10">
					<select name="v-kdsifat" id="v-kdsifat" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" title="Pilih Sifat Sub Kegiatan">
						<?php
						foreach($list_sifat_subkegiatan as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['KDSIFAT']; ?>" <?php echo setselected($r['KDSIFAT'], $kdsifat); ?>><?php echo $r['NMSIFAT']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Pagu Sub Kegiatan</label>
				<div class="col-sm-5">
					<input type="text" name='v-pagutif' value="<?php echo $pagutif; ?>" class="form-control mask-nu2d">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Pagu Sub Kegiatan (n+1)</label>
				<div class="col-sm-5">
					<input type="text" name="v-paguplus" value="<?php echo $paguplus; ?>" class="form-control mask-nu2d">
				</div>
			</div>
			<div class="form-group" style="<?php if ($kdtahap==4){echo "display:none";} ?>">
				<label class="col-sm-2 control-label">Pagu DPA</label>
				<div class="col-sm-5">
					<input type="text" name='v-pagutifdpa' value="<?php echo $pagutifdpa; ?>" class="form-control mask-nu2d" readonly>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Target Sebelum</label>
				<div class="col-sm-10">
					<input type="text" name="v-targetsebelum" value="<?php echo $targetsebelum; ?>" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label" style="<?php if ($kdtahap==4){$targe = "Target";} else {$targe = "Target Sesudah";} ?>"><?php echo $targe;?></label>
				<div class="col-sm-10">
					<input type="text" name="v-target" value="<?php echo $target; ?>" class="form-control">
				</div>

			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Lokasi</label>
				<div class="col-sm-10">
					<input type="text" name="v-lokasi" value="<?php echo $lokasi; ?>" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator Sub Kegiatan (output)</label>
				<div class="col-sm-10">
					<textarea name="v-ket" class="form-control" rows="5"><?php echo $ket; ?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">RG</label>
				<div class="col-sm-2">
					<div class="checkbox checkbox-primary checkbox-inline">
						<input type="checkbox" name="v-isres-gender" value="1" id="v_isres_gender" <?php echo setchecked('1', $is_res_gender); ?>>
						<label for="v-is_req_img"></label>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">SPM</label>
				<div class="col-sm-2">
					<div class="checkbox checkbox-primary checkbox-inline">
						<input type="checkbox" name="v-isspm" value="1" id="v-isspm" <?php echo setchecked('1', $is_spm); ?>>
						<label for="v-is_req_img"></label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">PKD</label>
				<div class="col-sm-2">
					<div class="checkbox checkbox-primary checkbox-inline">
						<input type="checkbox" name="v-ispkd" value="1" id="v-ispkd" <?php echo setchecked('1', $is_pkd); ?>>
						<label for="v-is_req_img"></label>
					</div>
				</div>
			</div>

			<?php if($this->sip->is_admin()): ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tanggal Valid</label>
				<div class="col-sm-2">
					<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#toggle-pengesahan-subkegiatan"><i class="fa fa-calendar-check-o"></i> Pengesahan</button>
				</div>
				<div class="col-sm-4">
					<div class="collapse" id="toggle-pengesahan-subkegiatan">
						<div class="input-group flatpickr">
							<input type="text" name="v-tglvalid" id="v-tglvalid" value="<?php echo $tglvalid; ?>" class="form-control text-center" placeholder="Pilih Tanggal" data-input>
							<div class="input-group-btn">
								<button type="button" class="btn btn-default" data-toggle><i class="fa fa-calendar"></i></button>
								<button type="button" class="btn btn-default" data-clear><i class="fa fa-times"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success <?php echo $curdShow; ?>"><i class="fa fa-download"></i> Simpan</button>
				</div>
			</div>

		</form>
	</div>
</div>
<script>
var blockSubKegiatanForm = '.block-subkegiatan-form ';

$(function() {
	updateMask(blockSubKegiatanForm);
	updateSelect(blockSubKegiatanForm);
	updateDate(blockSubKegiatanForm);

	$(document).off('click', blockSubKegiatanForm + '.btn-lookup-subkegiatan');
	$(document).on('click', blockSubKegiatanForm + '.btn-lookup-subkegiatan', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#v-unitkey'))) return false;
		if(isEmpty(getVal('#v-kegrkpdkey'))) return false;
		var data = {
			'l-unitkey' : getVal('#v-unitkey'),
			'l-kegrkpdkey' : getVal('#v-kegrkpdkey'),
			'setid'	: '#v-subkegrkpdkey',
			'setkd'	: '#v-nusubkeg',
			'setnm'	: '#v-nmsubkeg'
		}

		modalLookupSubKegiatan = new BootstrapDialog({
			title: 'Lookup Sub Kegiatan',
			type: 'type-primary',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/subkegiatan/', data)
		});
		modalLookupSubKegiatan.open();

		return false;
	});

	$(document).off('submit', blockSubKegiatanForm + '.form-subkegiatan');
	$(document).on('submit', blockSubKegiatanForm + '.form-subkegiatan', function(e) {
		e.preventDefault();
		$.post('/renja/subkegiatan_save/<?php echo $act; ?>', $(blockSubKegiatanForm + '.form-subkegiatan').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalSubKegiatanForm.close();
				$(blockRincian).show();
				$(blockSubKegiatan).show();
				dataLoadKegiatann();
				dataLoadSubKegiatan();

			}
		});

		return false;
	});
});
</script>
