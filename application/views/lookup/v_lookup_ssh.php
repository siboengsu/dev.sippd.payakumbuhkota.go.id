<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-ssh">
	<div class="col-md-12">
		<form class="form-horizontal form-load-lookup">
		<input type="hidden" value="1" class="page">
		<input type="hidden" name="l-kdrek" value="<?php echo $kdrek; ?>">
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Kode</label>
			<div class="col-sm-10">
				<input type="text" name="l-kdssh" id="l-kdssh" class="form-control input-sm" placeholder="Kode">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Nama</label>
			<div class="col-sm-10">
				<input type="text" name="l-ssh_nama" id="l-ssh_nama" class="form-control input-sm" placeholder="Nama">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Spesifikasi</label>
			<div class="col-sm-10">
				<input type="text" name="l-ssh_spek" id="l-ssh_spek" class="form-control input-sm" placeholder="Nama">
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
			<th class="text-nowrap">Aksi</th>
			<th class="text-nowrap">Kode</th>
			<th class="text-nowrap">Nama</th>
			<th class="text-nowrap">Spesifikasi</th>
			<th class="text-nowrap">Satuan</th>
			<th class="text-nowrap">Harga Satuan</th>
		</tr>
		<tbody class="data-load">
			<?php echo $ssh; ?>
		</tbody>
		</table>
		</div>
		<div class="text-center block-pagination"></div>
	</div>
</div>
<script>
var blockLookupSsh = '#lookup-ssh ';

function dataLoadLookupSsh() {
	var page = $(blockLookupSsh + '.page').val();
	$.post('/lookup/ssh_load/' + page, $(blockLookupSsh + '.form-load-lookup').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockLookupSsh + '.data-load').html(res);
			updateNum(blockLookupSsh);
		}
	});
}

$(function() {
	updateNum(blockLookupSsh);
	
	$(document).off('submit', blockLookupSsh + '.form-load-lookup');
	$(document).on('submit', blockLookupSsh + '.form-load-lookup', function(e) {
		e.preventDefault();
		$(blockLookupSsh + '.page').val('1');
		dataLoadLookupSsh();
		return false;
	});
	
	$(document).off('click', blockLookupSsh + '.btn-page');
	$(document).on('click', blockLookupSsh + '.btn-page', function(e) {
		e.preventDefault();
		$(blockLookupSsh + '.page').val($(this).data('ci-pagination-page'));
		dataLoadLookupSsh();
		return false;
	});
	
	$(document).off('click', blockLookupSsh + '.btn-select');
	$(document).on('click', blockLookupSsh + '.btn-select', function(e) {
		e.preventDefault();
		var tr = $(this).closest('tr');
		var setkode = tr.data('id'),
			setnama = tr.find('td:eq(2)').text().trim(),
			setspek0 = tr.find('td:eq(3)').text().trim(),
			setsatuan = tr.find('td:eq(4)').text().trim(),
			setharga = tr.find('td:eq(5)').text().trim();
			
		if (setspek0=="-") {
			 setspek = '';
		}else {
			setspek = setspek0;
		}
			
		$('<?php echo $setkode; ?>').val(setkode);
		$('<?php echo $setnama; ?>').val(setnama + ((setspek != '') ? '  ' + setspek : '')).prop('readonly', true);
		$('<?php echo $setsatuan; ?>').val(setsatuan).prop('readonly', true);
		$('<?php echo $setharga; ?>').val(setharga).prop('readonly', true);
		
		modalLookupSsh.close();
		
		return false;
	});
});
</script>
