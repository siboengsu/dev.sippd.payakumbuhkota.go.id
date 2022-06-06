<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-sasaran-nas">
	<div class="col-md-12">
		<form class="form-horizontal form-load-lookup">

			<input type="hidden" name="l-prionaskey" value="<?php echo $prionaskey; ?>">

			<div class="form-group">
				<label class="col-sm-2 control-label">Nomor</label>
				<div class="col-sm-10">
					<input type="text" name="l-nosas" id="l-nosas" class="form-control input-sm" placeholder="Nomor">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Uraian</label>
				<div class="col-sm-10">
					<input type="text" name="l-nmsas" id="l-nmsas" class="form-control input-sm" placeholder="Uraian">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Cari</button>
				</div>
			</div>
		</form>
	</div>

	<form class="form-add">
		<input type="hidden" id="f-unitkey" name="f-unitkey" value="<?php echo $unitkey; ?>">
		<input type="hidden" id="f-pgrmrkpdkey" name="f-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
		<input type="hidden" id="f-prionaskey" name="f-prionaskey" value="<?php echo $prionaskey; ?>">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Aksi</th>
			<th>Nomor</th>
			<th>Uraian</th>
		</tr>
		<tbody class="data-load">
			<?php echo $sasaran_nasional; ?>
		</tbody>
		</table>
		</div>
			<div class="text-center block-pagination"></div>
	</div>
	<div class="col-md-12">
		<button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Simpan</button>
	</div>
	</form>
</div>
<script>
var blockLookupSasaranNas = '#lookup-sasaran-nas ';

function dataLoadLookupSasaranNas() {
	$.post('/lookup/sasaran_nasional_load/', $(blockLookupSasaranNas + '.form-load-lookup').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockLookupSasaranNas + '.data-load').html(res);
		}
	});
}



$(function() {
	$(document).off('submit', blockLookupSasaranNas + '.form-load-lookup');
	$(document).on('submit', blockLookupSasaranNas + '.form-load-lookup', function(e) {
		e.preventDefault();
		dataLoadLookupSasaranNas();
		return false;
	});

	$(document).off('submit', blockLookupSasaranNas + '.form-add');
	$(document).on('submit', blockLookupSasaranNas + '.form-add', function(e) {
		e.preventDefault();

		if($(blockLookupSasaranNas + ".form-add input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}

		$.post('/renja/sasaran_save_nasional/', $(blockLookupSasaranNas + '.form-add').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadSasaranNasional();
				modalSasaranNasForm.close();
			}
		});

		return false;
	});
});
</script>
