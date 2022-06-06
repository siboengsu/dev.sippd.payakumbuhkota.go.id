<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$kdtahap = $this->KDTAHAP = $this->session->KDTAHAP;
?>
<div class="row block-kegiatan-form">
	<div class="col-md-12">
		<form class="form-horizontal form-kegiatan">
			<input type="hidden" id="i-unitkey" name="i-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="i-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
			<input type="hidden" id="i-kegrkpdkey" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">

			<div class="form-group">
				<label class="col-sm-2 control-label">Kegiatan</label>
				<div class="col-sm-2">
					<input type="text" id="v-nukeg" value="<?php echo $nukeg; ?>" class="form-control text-bold" readonly>
				</div>
				<div class="form-gap visible-xs-block"></div>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-lookup-kegiatan"><i class="fa fa-folder-open"></i></button>
						</span>
						<input type="text" id="v-nmkeg" value="<?php echo $nmkeg; ?>" class="form-control text-bold" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Sifat Kegiatan</label>
				<div class="col-sm-10">
					<select name="i-kdsifat" id="i-kdsifat" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" title="Pilih Sifat Kegiatan">
						<?php
						foreach($list_sifat as $r):
						$r = settrim($r); ?>
						<option value="<?php echo $r['KDSIFAT']; ?>" <?php echo setselected($r['KDSIFAT'], $kdsifat); ?>><?php echo $r['NMSIFAT']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

	<div style="display:none;">
			<div class="form-group">
				<label class="col-sm-2 control-label">Pagu</label>
				<div class="col-sm-5">
					<input type="text" name='i-pagutif' value="<?php echo $pagutif; ?>" class="form-control mask-nu2d">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Pagu (n+1)</label>
				<div class="col-sm-5">
					<input type="text" name="i-paguplus" value="<?php echo $paguplus; ?>" class="form-control mask-nu2d">
				</div>
			</div>
	</div>
			<div class="form-group" style="<?php if ($kdtahap==4){echo "display:none";} ?>">
				<label class="col-sm-2 control-label">Pagu DPA</label>
				<div class="col-sm-5">
					<input type="text" name='i-pagutifdpa' value="<?php echo $pagutifdpa; ?>" class="form-control mask-nu2d" readonly>
				</div>
			</div>

			<div class="form-group" style="<?php if ($kdtahap==4){echo "display:none";} ?>">
				<label class="col-sm-2 control-label">Target Sebelum</label>
				<div class="col-sm-10">
					<input type="text" name="i-targetsebelum" value="<?php echo $targetsebelum; ?>" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label" style="<?php if ($kdtahap==4){$targe = "Target";} else {$targe = "Target Sesudah";} ?>"><?php echo $targe;?></label>
				<div class="col-sm-10">
					<input type="text" name="i-kuantitatif" value="<?php echo $kuantitatif; ?>" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Lokasi</label>
				<div class="col-sm-10">
					<input type="text" name="i-lokasi" value="<?php echo $lokasi; ?>" class="form-control">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Indikator Kegiatan (outcome)</label>
				<div class="col-sm-10">
					<textarea name="i-ket" class="form-control" rows="5"><?php echo $ket; ?></textarea>
				</div>
			</div>

				<div class="form-group" style="display:none;">
				<label class="col-sm-2 control-label">Responsive Gender</label>
				<div class="col-sm-2">
					<div class="checkbox checkbox-primary checkbox-inline">
						<input type="checkbox" name="i-isres-gender" value="1" id="i_isres_gender" <?php echo setchecked('1', $is_res_gender); ?>>
						<label for="i-is_req_img"></label>
					</div>
				</div>
			</div>

			<?php if($this->sip->is_admin()): ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Tanggal Valid</label>
				<div class="col-sm-2">
					<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#toggle-pengesahan"><i class="fa fa-calendar-check-o"></i> Pengesahan</button>
				</div>
				<div class="col-sm-4">
					<div class="collapse" id="toggle-pengesahan">
						<div class="input-group flatpickr">
							<input type="text" name="i-tglvalid" id="i-tglvalid" value="<?php echo $tglvalid; ?>" class="form-control text-center" placeholder="Pilih Tanggal" data-input>
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
var blockKegiatanForm = '.block-kegiatan-form ';

$(function() {
	updateMask(blockKegiatanForm);
	updateSelect(blockKegiatanForm);
	updateDate(blockKegiatanForm);

	$(document).off('click', blockKegiatanForm + '.btn-lookup-kegiatan');
	$(document).on('click', blockKegiatanForm + '.btn-lookup-kegiatan', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#i-unitkey'))) return false;
		if(isEmpty(getVal('#i-pgrmrkpdkey'))) return false;
		var data = {
			'l-unitkey' : getVal('#i-unitkey'),
			'l-pgrmrkpdkey' : getVal('#i-pgrmrkpdkey'),
			'setid'	: '#i-kegrkpdkey',
			'setkd'	: '#v-nukeg',
			'setnm'	: '#v-nmkeg'
		}

		modalLookupKegiatan = new BootstrapDialog({
			title: 'Lookup Kegiatan',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/kegiatan/', data)
		});
		modalLookupKegiatan.open();

		return false;
	});

	$(document).off('submit', blockKegiatanForm + '.form-kegiatan');
	$(document).on('submit', blockKegiatanForm + '.form-kegiatan', function(e) {
		e.preventDefault();
		$.post('/renja/kegiatan_save/<?php echo $act; ?>', $(blockKegiatanForm + '.form-kegiatan').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				modalKegiatanForm.close();
				dataLoadKegiatan();
			}
		});

		return false;
	});
});
</script>
