<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Matrik 5.3 Perubahan Menurut Perangkat Daerah</h1>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal" id="matrik53_cetak_perubahan">
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipe Report</label>
						<div class="col-sm-8">
							<select name="f-format" id="reportformat" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" id="reportformat">
								<option value="1">Pdf</option>

							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="tglcetak" class="col-sm-2 control-label">Tanggal</label>
						<div class="col-sm-2">
							<div class="input-group flatpickr">
								<input type="text" name="f-tanggal" id="f-tanggal" value="<?php echo date("d-m-Y"); ?>" class="form-control text-center" data-input disabled>
								<div class="input-group-btn">
									<button type="button" class="btn btn-default" aria-label="Bold" data-toggle>
										<i class="fa fa-calendar"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="tglcetak" class="col-sm-2 control-label">Nomor Dimulai Dari</label>
						<div class="col-sm-2">
							<div class="input-group">
								<input type="text" name="f-nomor" id="f-nomor" value="" class="form-control text-center">

							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-8">
							<button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Cetak</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
var blockReport = '.block-report ';

$(function() {
	updateSelect();
	updateDate();

	$('#matrik53_cetak_perubahan').submit(function(e) {
		e.preventDefault();
			var nomor = $('#f-nomor').val();
			var id = $('#reportformat').val();
		if (id=="1") {

		$.post('Report_matrik/c_rekapitulasi_matrik53/'+nomor, $(this).serialize(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				window.open("Report_matrik/c_rekapitulasi_matrik53/"+nomor, "_blank");
			}
		});
		} else if (id=="2") {


		} else {

	}
		return false;
	});


});
</script>
