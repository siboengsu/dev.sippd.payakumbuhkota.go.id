<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-hspk2">
	<div class="col-md-12">
		<form class="form-horizontal form-load-lookup">
		<input type="hidden" value="1" class="page">
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Kode</label>
			<div class="col-sm-10">
				<input type="text" name="l-kdhspk2" id="l-kdhspk2" class="form-control input-sm" placeholder="Kode">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Nama</label>
			<div class="col-sm-10">
				<input type="text" name="l-hspk2_nama" id="l-hspk2_nama" class="form-control input-sm" placeholder="Nama">
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
			<th>Kode</th>
			<th>Nama</th>
		</tr>
		<tbody class="data-load">
			<?php echo $hspk2; ?>
		</tbody>
		</table>
		</div>
		<div class="text-center block-pagination"></div>
	</div>
</div>
<script>
var blockLookupHspk2 = '#lookup-hspk2 ';

function dataLoadLookupHspk2() {
	var page = $(blockLookupHspk2 + '.page').val();
	$.post('/lookup/hspk2_load/' + page, $(blockLookupHspk2 + '.form-load-lookup').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockLookupHspk2 + '.data-load').html(res);
		}
	});
}

$(function() {
	$(document).off('submit', blockLookupHspk2 + '.form-load-lookup');
	$(document).on('submit', blockLookupHspk2 + '.form-load-lookup', function(e) {
		e.preventDefault();
		$(blockLookupHspk2 + '.page').val('1');
		dataLoadLookupHspk2();
		return false;
	});
	
	$(document).off('click', blockLookupHspk2 + '.btn-page');
	$(document).on('click', blockLookupHspk2 + '.btn-page', function(e) {
		e.preventDefault();
		$(blockLookupHspk2 + '.page').val($(this).data('ci-pagination-page'));
		dataLoadLookupHspk2();
		return false;
	});
	
	$(document).off('click', blockLookupHspk2 + '.btn-select');
	$(document).on('click', blockLookupHspk2 + '.btn-select', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').data('id'),
			data = {
				'setkode'	: '<?php echo $setkode; ?>',
				'setifno'	: '<?php echo $setifno; ?>',
				'setnmpek'	: '<?php echo $setnmpek; ?>',
				'setjnpek'	: '<?php echo $setjnpek; ?>',
				'setsatuan'	: '<?php echo $setsatuan; ?>',
				'setharga'	: '<?php echo $setharga; ?>'
			};
		
		modalLookupHspk3 = new BootstrapDialog({
			title: 'Lookup HSPK (Jenis Pekerjaan)',
			type: 'type-info',
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/hspk3/' + id, data)
		});
		modalLookupHspk3.open();
		
		return false;
	});
});
</script>
