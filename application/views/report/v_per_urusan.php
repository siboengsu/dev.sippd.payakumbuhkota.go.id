<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Plafon Anggaran Per Urusan</h1>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal" id="plafon-urusan-cetak">
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipe Report</label>
						<div class="col-sm-8">
							<select name="f-format" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" id="reportformat">
								<option value="1">Pdf</option>
								<option value="2">Excel</option>
							</select>
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
	$('#plafon-urusan-cetak').submit(function(e) {
		e.preventDefault();

			var id = $('#reportformat').val();
		if (id=="1") {
				$.post('report/per_urusan_print', $(this).serialize(), function(res, status, xhr) {
					if(contype(xhr) == 'json') {
							respond(res);
						} else {
						window.open("report/per_urusan_print/print", "_blank");
						}
				});
			} else {
			$.post('report/createXLS_rekapurusan', $(this).serialize(), function(res, status, xhr) {
				if(contype(xhr) == 'json') {
					respond(res);
				} else {
					window.open("report/createXLS_rekapurusan", "_blank");
				}
			});
		}
		return false;
		
	});


});
</script>
