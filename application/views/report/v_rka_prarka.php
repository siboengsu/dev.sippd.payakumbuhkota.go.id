<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Belanja Langsung Pra RKA</h1>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal" id ="prarka-cetak" name="prarka-cetak">
					<input type="hidden" name="f-unitkey" id="f-unitkey" value="<?php echo $this->session->UNITKEY; ?>" required>
					<input type="hidden" name="f-kegrkpdkey" id="f-kegrkpdkey" required>
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipe Report</label>
						<div class="col-sm-8">
							<select name="f-format" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto"  id="reportformat">
								<option value="1">Pdf</option>
								<option value="2">Excel</option>
							</select>
						</div>
					</div>

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
						<label class="col-sm-2 control-label">Kegiatan</label>
						<div class="col-sm-2">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
								<input type="text" id="v-nukeg" class="form-control text-bold" placeholder="Kode Kegiatan" readonly>
							</div>
						</div>

						<div class="form-gap visible-xs-block"></div>

						<div class="col-sm-8">
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-lookup-programkegiatan"><i class="fa fa-folder-open"></i></button>
								</span>
								<input type="text" id="v-nmkeg" class="form-control text-bold" placeholder="Nama Kegiatan" readonly>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="tglcetak" class="col-sm-2 control-label">Tanggal</label>
						<div class="col-sm-2">
							<div class="input-group flatpickr">
								<input type="text" name="f-tanggal" id="f-tanggal" value="<?php echo date("d-m-Y"); ?>" class="form-control text-center" data-input>
								<div class="input-group-btn">
									<button type="button" class="btn btn-default" aria-label="Bold" data-toggle>
										<i class="fa fa-calendar"></i>
									</button>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-8">
							<button type="submit" class="btn btn-info" id ="btn-prarka-cetak"><i class="fa fa-print"></i> Cetak</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
var prarkacetak = '.prarka-cetak ';

$(function() {
	updateSelect();
	updateDate();

		// $(document).off('change', blockReport + '#f-unitkey');
	// $(document).on('change', blockReport + '#f-unitkey', function(e) {
		// e.preventDefault();
		// $('#f-kegrkpdkey').val('');
		// $('#v-nukeg, #v-nmkeg').val('');
	// });

	// // Rekening
	// $(document).off('click', '.btn-lookup-programkegiatan');
	// $(document).on('click', '.btn-lookup-programkegiatan', function(e) {
		// e.preventDefault();
		// if(isEmpty(getVal('#f-unitkey'))) return false;
		// var data = {
			// 'unitkey'	: getVal('#f-unitkey'),
			// 'setid'		: '#f-kegrkpdkey',
			// 'setkd'		: '#v-nukeg',
			// 'setnm'		: '#v-nmkeg'
		// }

		// modalLookupProgramKegiatan = new BootstrapDialog({
			// title: 'Lookup Kegiatan',
			// type: 'type-info',
			// size: 'size-wide',
			// message: $('<div></div>').load('/lookup/program_kegiatan/', data)
		// });
		// modalLookupProgramKegiatan.open();

		// return false;
	// });



	$('#f-unitkey').change(function(e) {
		 e.preventDefault();
		 $('#f-kegrkpdkey').val('');
		 $('#v-nukeg, #v-nmkeg').val('');
	 });

	 // Rekening

	 $('.btn-lookup-programkegiatan').click (function(e) {
		 e.preventDefault();
		 if(isEmpty(getVal('#f-unitkey'))) return false;
		 var data = {
			 'unitkey'	: getVal('#f-unitkey'),
			 'setid'		: '#f-kegrkpdkey',
			 'setkd'		: '#v-nukeg',
			 'setnm'		: '#v-nmkeg'
		 }

		 modalLookupProgramKegiatan = new BootstrapDialog({
			 title: 'Lookup Kegiatan',
			 type: 'type-info',
			 size: 'size-wide',
			 message: $('<div></div>').load('/lookup/program_kegiatan/', data)
		 });
		 modalLookupProgramKegiatan.open();

		 return false;
	 });



	 $('#btn-prarka-cetak').click (function(e) {
		 e.preventDefault();
		 var id = $('#f-unitkey').val();
		 var nama = $('#v-nmunit').val();
		 var kegrkpdkey = $('#f-kegrkpdkey').val();
		 var nmkeg = $('#v-nukeg').val();
		 var idprint = $('#reportformat').val();
		 if (idprint=="1") {
			 $.post('report/cetak_prarka/'+id+'/'+kegrkpdkey, $(this).serializeArray(), function(res, status, xhr) {
				if(contype(xhr) == 'json') {
					 respond(res);
				} else {
					 window.open('report/cetak_prarka/print/'+id+'/'+kegrkpdkey, '_blank');
				}
			});
		} else {
			$.post('report/createXLSprarka/'+id+'/'+kegrkpdkey, $(this).serializeArray(), function(res, status, xhr) {
			 if(contype(xhr) == 'json') {
				 respond(res);
			 } else {
				 window.open('report/createXLSprarka/'+id+'/'+kegrkpdkey, '_blank');
			 }
			});

		}
		 
		 return false;
	 });


});
</script>
