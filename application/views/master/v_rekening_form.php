<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row master-rekeningssh" id="masterrekeningssh">
	<div class="col-md-12">
		<form class="form-horizontal form-load-master-rekening">
		<input type="hidden" value="1" class="page">
		<div class="form-group">
			<label class="col-sm-2 control-label">Nomor Rekening</label>
			<div class="col-sm-10">
				<input type="text" name="l-kdrek" id="l-kdrek" class="form-control input-sm" placeholder="Nomor Rekening">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Uraian</label>
			<div class="col-sm-10">
				<input type="text" name="l-nmper" id="l-nmper" class="form-control input-sm" placeholder="Uraian">
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
			<th>Nomor Rekening</th>
			<th>Uraian</th>
		</tr>
		<tbody class="data-load">
			<?php echo $sshrekening; ?>
		</tbody>
		</table>
		</div>
		<div class="text-center block-pagination"></div>
	</div>
</div>
<script>

var blockRekeningSSH = '#masterrekeningssh ';

function dataLoadLookupRekeningSSH() {
	var page = $(blockRekeningSSH + '.page').val();
	$.post('/master/rekeningSSH_load/'+ page, $(blockRekeningSSH + '.form-load-master-rekening').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockRekeningSSH + '.data-load').html(res);
		}
	});
}

$(function() {
	$(document).off('submit', blockRekeningSSH + '.form-load-master-rekening');
	$(document).on('submit', blockRekeningSSH + '.form-load-master-rekening', function(e) {
		e.preventDefault();
		$(blockRekeningSSH + '.page').val('1');
		dataLoadLookupRekeningSSH();
		return false;
	});
	
	$(document).off('click', blockRekeningSSH + '.btn-page');
	$(document).on('click', blockRekeningSSH + '.btn-page', function(e) {
		e.preventDefault();
		$(blockRekeningSSH + '.page').val($(this).data('ci-pagination-page'));
		dataLoadLookupRekeningSSH();
		return false;
	});
	
	$(document).off('click', blockRekeningSSH + '.btn-select');
	$(document).on('click', blockRekeningSSH + '.btn-select', function(e) {
		e.preventDefault();
		var tr = $(this).closest('tr');
		var setid = tr.data('id'),
			setkd = tr.find('td:eq(1)').text(),
			setnm = tr.find('td:eq(2)').text();
			
		$('<?php echo $setkd; ?>').val(setkd).prop('readonly', true);;
		$('<?php echo $setnm; ?>').val(setnm).prop('readonly', true);;

		modalLookupRekeningSSH.close();

		return false;
	});
	
	
});

</script>
