<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-kegiatan">
	<div class="col-md-12">
		<form class="form-horizontal form-load-lookup">
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Nomor</label>
			<div class="col-sm-10">
				<input type="text" name="l-nokeg" id="l-noprioppas" class="form-control input-sm" placeholder="Nomor">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Uraian</label>
			<div class="col-sm-10">
				<input type="text" name="l-nmkeg" id="l-nmprioppas" class="form-control input-sm" placeholder="Uraian">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i> Cari</button>
			</div>
		</div>
		</form>
	</div>
	
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Aksi</th>
			<th>Nomor</th>
			<th>Uraian</th>
		</tr>
		<tbody class="data-load">
			<?php echo $kegiatan; ?>
		</tbody>
		</table>
		</div>
	</div>
</div>
<script>
var blockLookupKegiatan = '#lookup-kegiatan ';

function dataLoadLookupKegiatan() {
	$.post('/lookup/kegiatan_load/', $(blockLookupKegiatan + '.form-load-lookup').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockLookupKegiatan + '.data-load').html(res);
		}
	});
}

$(function() {
	$(document).off('submit', blockLookupKegiatan + '.form-load-lookup');
	$(document).on('submit', blockLookupKegiatan + '.form-load-lookup', function(e) {
		e.preventDefault();
		dataLoadLookupKegiatan();
		return false;
	});
	
	$(document).off('click', blockLookupKegiatan + '.btn-select');
	$(document).on('click', blockLookupKegiatan + '.btn-select', function(e) {
		e.preventDefault();
		var tr = $(this).closest('tr');
		var setid = tr.data('id'),
			setkd = tr.find('td:eq(1)').text(),
			setnm = tr.find('td:eq(2)').text();
		
		$('<?php echo $setid; ?>').val(setid.trim()).change();
		$('<?php echo $setkd; ?>').val(setkd.trim()).change();
		$('<?php echo $setnm; ?>').val(setnm.trim()).change();
		
		modalLookupKegiatan.close();
		
		return false;
	});
});
</script>