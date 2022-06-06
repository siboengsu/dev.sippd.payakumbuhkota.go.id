<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Laporan SSH</h1>
		</div>
	</div>
</div>

<div class="row cetak-laporan-ssh">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal form-load" id="ssh-cetak">
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipe Report</label>
						<div class="col-sm-8">
							<select name="f-format" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto"  id="reportformat">
								<option value="1">Pdf</option>
								<option value="2">Excel</option>
								<option value="3">Word</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Kode Rekening</label>
						<div class="col-sm-3">
								<div class="input-group">
									<span class="input-group-btn">
										<button type="button" class="btn btn-default btn-lookup-rek"><i class="fa fa-folder-open"></i></button>
									</span>
									<input type="text" name="i-kdrek" id="i-kdrek" value="" class="form-control text-bold" readonly>

								</div>
							</div>
						</div>

						<div class="form-group">
						<label class="col-sm-2 control-label">Uraian</label>
						<div class="col-sm-6">
								<input type="text" name="i-nmper" id="i-nmper" value=""  class="form-control" readonly>


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
var cetakLaporanSSH = '.cetak-laporan-ssh ';

$(function() {
	updateSelect();
	updateDate();

	$('#ssh-cetak').submit(function(e) {
		e.preventDefault();

			var id = $('#reportformat').val(),
			kdrek =  $('#i-kdrek').val();

			console.log(kdrek);
		if (id=="1") {
			$.post('report/cetak_ssh/'+ kdrek , $(this).serialize(), function(res, status, xhr) {
				if(contype(xhr) == 'json') {
					respond(res);
				} else {
					window.open('report/cetak_ssh/print/'+ kdrek, "_blank");
				}
					});
		} else if (id=="2") {
			$.post('report/createXLSSH/'+ kdrek, $(this).serialize(), function(res, status, xhr) {
				if(contype(xhr) == 'json') {
					respond(res);
				} else {
					window.open('report/createXLSSH/'+ kdrek, "_blank");
				}
			});
		}else {
			$.post('report/cetak_SSH_word/'+ kdrek, $(this).serialize(), function(res, status, xhr) {
				if(contype(xhr) == 'json') {
					respond(res);
				} else {
					window.open('report/cetak_SSH_word/print/'+ kdrek, "_blank");
				}
			});
		}
		return false;
	});

	$(document).off('click', cetakLaporanSSH + '.btn-lookup-rek');
	$(document).on('click', cetakLaporanSSH + '.btn-lookup-rek', function(e) {
		e.preventDefault();
		var data = {

			'setkd' : '#i-kdrek',
			'setnm' : '#i-nmper'


		};

		modalLookupRekeningSSH = new BootstrapDialog({
			title: 'Lookup Rekening',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/master/rekeningSSH/', data)
		});
		modalLookupRekeningSSH.open();

		return false;
	});


});
</script>
