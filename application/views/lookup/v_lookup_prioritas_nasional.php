<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-prioritas-nas">
	<div class="col-md-12">
		<form class="form-horizontal form-load-lookup">

		<div class="form-group">
			<label class="col-sm-2 control-label">Nomor</label>
			<div class="col-sm-10">
				<input type="text" name="l-noprio" id="l-nuprio" class="form-control input-sm" placeholder="Nomor">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Uraian</label>
			<div class="col-sm-10">
				<input type="text" name="l-nmprio" id="l-nmprio" class="form-control input-sm" placeholder="Uraian">
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
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Aksi</th>
			<th>Nomor</th>
			<th>Uraian</th>
		</tr>
		<tbody class="data-load">
			<?php echo $lookup_prioritas_nasional; ?>
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
var blockLookupPrioritasNas = '#lookup-prioritas-nas ';

function dataLoadLookupPrioritasNas() {
	$.post('/lookup/prioritas_load_nasional/', $(blockLookupPrioritasNas + '.form-load-lookup').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockLookupPrioritasNas + '.data-load').html(res);
		}
	});
}

$(function() {
	$(document).off('submit', blockLookupPrioritasNas + '.form-load-lookup');
	$(document).on('submit', blockLookupPrioritasNas + '.form-load-lookup', function(e) {
		e.preventDefault();
		dataLoadLookupPrioritasNas();
		return false;
	});

	$(document).off('submit', blockLookupPrioritasNas + '.form-add');
	$(document).on('submit', blockLookupPrioritasNas + '.form-add', function(e) {
		e.preventDefault();
		if($(blockLookupPrioritasNas + ".form-add input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}

		$.post('/renja/prioritas_save_nasional/', $(blockLookupPrioritasNas + '.form-add').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadLookupPrioritasNasional();
				modalPrioritasNasionalForm.close();
			}
		});

		return false;
	});
});
</script>
