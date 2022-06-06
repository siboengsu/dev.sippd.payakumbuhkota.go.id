<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Pagu Per OPD Perubahan</h1>
		</div>
	</div>
</div>

<div class="row block-report">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<div class="form-group">
						<label class="col-sm-2 control-label">Tipe Report</label>
						<div class="col-sm-8">
							<select name="f-format" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
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
	
	$(document).off('submit', blockReport + '.form-load');
	$(document).on('submit', blockReport + '.form-load', function(e) {
		e.preventDefault();
		$.post('/report/perubahan/rkpd_pagu_opd/open', $(blockReport + '.form-load').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				window.open(res, '_blank');
			}
		});
		
		return true;
	});
});
</script>

