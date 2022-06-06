<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-user">
	<div class="col-md-12">
		<form class="form-horizontal form-load-lookup">
		<input type="hidden" value="1" class="page">
		
		<div class="form-group">
			<label class="col-sm-2 control-label">User ID</label>
			<div class="col-sm-10">
				<input type="text" name="l-userid" id="l-userid" class="form-control input-sm" placeholder="User ID">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">NIP</label>
			<div class="col-sm-10">
				<input type="text" name="l-nip" id="l-nip" class="form-control input-sm" placeholder="NIP">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Nama</label>
			<div class="col-sm-10">
				<input type="text" name="l-nama" id="l-nama" class="form-control input-sm" placeholder="Nama">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Kode Unit</label>
			<div class="col-sm-10">
				<input type="text" name="l-kdunit" id="l-kdunit" class="form-control input-sm" placeholder="Kode Unit">
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
			<th>User ID</th>
			<th>NIP</th>
			<th>Nama</th>
			<th>Unit</th>
		</tr>
		<tbody class="data-load">
			<?php echo $user; ?>
		</tbody>
		</table>
		</div>
		<div class="text-center block-pagination"></div>
	</div>
</div>
<script>
var blockLookupUser = '#lookup-user ';

function dataLoadLookupUser() {
	var page = $(blockLookupUser + '.page').val();
	$.post('/lookup/user_load/'+page, $(blockLookupUser + '.form-load-lookup').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockLookupUser + '.data-load').html(res);
		}
	});
}

$(function() {
	$(document).off('submit', blockLookupUser + '.form-load-lookup');
	$(document).on('submit', blockLookupUser + '.form-load-lookup', function(e) {
		e.preventDefault();
		$(blockLookupUser + '.page').val('1');
		dataLoadLookupUser();
		return false;
	});
	
	$(document).off('click', blockLookupUser + '.btn-page');
	$(document).on('click', blockLookupUser + '.btn-page', function(e) {
		e.preventDefault();
		$(blockLookupUser + '.page').val($(this).data('ci-pagination-page'));
		dataLoadLookupUser();
		return false;
	});
	
	$(document).off('click', blockLookupUser + '.btn-select');
	$(document).on('click', blockLookupUser + '.btn-select', function(e) {
		e.preventDefault();
		var tr = $(this).closest('tr');
		var setid = tr.data('id'),
			setnm = tr.find('td:eq(3)').text();
		
		$('<?php echo $setid; ?>').val(setid.trim()).change();
		$('<?php echo $setnm; ?>').val(setnm.trim()).change();
		
		modalLookupUser.close();
		
		return false;
	});
});
</script>