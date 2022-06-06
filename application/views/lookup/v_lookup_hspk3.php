<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-hspk3">
	<div class="col-md-12">
		<form class="form-horizontal form-load-lookup">
		<input type="hidden" value="1" class="page">
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Kode</label>
			<div class="col-sm-10">
				<input type="text" name="l-kdhspk3" id="l-kdhspk3" class="form-control input-sm" placeholder="Kode">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Nama</label>
			<div class="col-sm-10">
				<input type="text" name="l-hspk3_nama" id="l-hspk3_nama" class="form-control input-sm" placeholder="Nama">
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
			<th>Satuan</th>
			<th>Harga Satuan</th>
		</tr>
		<tbody class="data-load">
			<?php echo $hspk3; ?>
		</tbody>
		</table>
		</div>
		<div class="text-center block-pagination"></div>
	</div>
</div>
<script>
var blockLookupHspk3 = '#lookup-hspk3 ';

function dataLoadLookupHspk3() {
	var kdhspk2 = '<?php echo $kdhspk2; ?>',
		page = $(blockLookupHspk3 + '.page').val();
	$.post('/lookup/hspk3_load/' + kdhspk2 + '/' + page, $(blockLookupHspk3 + '.form-load-lookup').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockLookupHspk3 + '.data-load').html(res);
		}
	});
}

$(function() {
	updateNum(blockLookupHspk3);
	
	$(document).off('submit', blockLookupHspk3 + '.form-load-lookup');
	$(document).on('submit', blockLookupHspk3 + '.form-load-lookup', function(e) {
		e.preventDefault();
		$(blockLookupHspk3 + '.page').val('1');
		dataLoadLookupHspk3();
		return false;
	});
	
	$(document).off('click', blockLookupHspk3 + '.btn-page');
	$(document).on('click', blockLookupHspk3 + '.btn-page', function(e) {
		e.preventDefault();
		$(blockLookupHspk3 + '.page').val($(this).data('ci-pagination-page'));
		dataLoadLookupHspk3();
		return false;
	});
	
	$(document).off('click', blockLookupHspk3 + '.btn-select');
	$(document).on('click', blockLookupHspk3 + '.btn-select', function(e) {
		e.preventDefault();
		var tr = $(this).closest('tr');
		var setkode = tr.data('id'),
			setifno = tr.data('id'),
			setnmpek = tr.data('hspk2_nama'),
			setjnpek = tr.find('td:eq(2)').text(),
			setsatuan = tr.find('td:eq(3)').text(),
			setharga = tr.find('td:eq(4)').text();
		
		$('<?php echo $setkode; ?>').val(setkode.trim());
		$('<?php echo $setifno; ?>').data('id', setifno.trim());
		$('<?php echo $setnmpek; ?>').val(setnmpek.trim()).prop('readonly', true);
		$('<?php echo $setjnpek; ?>').val(setjnpek.trim()).prop('readonly', true);
		$('<?php echo $setsatuan; ?>').val(setsatuan.trim()).prop('readonly', true);
		$('<?php echo $setharga; ?>').val(setharga.trim()).prop('readonly', true);
		
		modalLookupHspk2.close();
		modalLookupHspk3.close();
		
		return false;
	});
});
</script>
