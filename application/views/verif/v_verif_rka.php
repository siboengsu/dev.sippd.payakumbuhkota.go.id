<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Verifikasi Pra RKA Non SSH/SB</h1>
		</div>
	</div>
</div>

<div class="row block-verif">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<input type="hidden" name="f-unitkey" id="f-unitkey" value="<?php echo $this->session->UNITKEY; ?>" required>
					<input type="hidden" value="1" class="page">
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Unit Organisasi</label>
						<div class="col-sm-2">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
								<input type="text" value="<?php echo $this->session->KDUNIT; ?>" id="v-kdunit" class="form-control text-bold" placeholder="Kode Unit" readonly>
							</div>
						</div>
						
						<div class="form-gap visible-xs-block"></div>
						
						<div class="col-sm-8">
							<?php if($this->sip->is_admin()): ?>
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
								</span>
								<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
							</div>
							<?php else: ?>
							<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Pencarian</label>
						<div class="col-sm-2">
							<input type="text" name="f-search_key" class="form-control">
						</div>
						
						<div class="form-gap visible-xs-block"></div>
						
						<div class="col-sm-8">
							<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
								<option value="1">Program</option>
								<option value="2">Kegiatan</option>
								<option value="3">Rekening</option>
								<option value="4">Uraian</option>
							</select>
							<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Pra RKA</div>
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-hover f12">
			<tr>
				<th style="width:20px;">&nbsp;</th>
				<th style="width:20px;"></th>
				<th style="width:20px;"></th>
				<th class="w1px text-center text-nowrap va-mid">Kode</th>
				<th class="text-center text-nowrap va-mid">Uraian</th>
				<th class="text-center text-nowrap va-mid">Volume</th>
				<th class="text-center text-nowrap va-mid">Satuan</th>
				<th class="text-center text-nowrap va-mid">Tarif</th>
				<th class="text-center text-nowrap va-mid">Jumlah</th>
			</tr>
			<tbody class="data-load"><?php echo $verif; ?></tbody>
			</table>
			</div>
		</div>
	</div>
</div>

<script>
var blockVerif = '.block-verif ';

function dataLoadVerif() {
	if(isEmpty(getVal('#f-unitkey'))) return false;
	$.post('/user/verif_rka_load/', $(blockVerif + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockVerif + '.data-load').html(res);
			updateNum(blockVerif);
		}
	});
}

$(function() {
	updateSelect();
	
	$(document).off('change', blockVerif + '#f-unitkey');
	$(document).on('change', blockVerif + '#f-unitkey', function(e) {
		e.preventDefault();
		dataLoadVerif();
	});
	
	$(document).off('submit', blockVerif + '.form-load');
	$(document).on('submit', blockVerif + '.form-load', function(e) {
		e.preventDefault();
		dataLoadVerif();
		return false;
	});
});
</script>

