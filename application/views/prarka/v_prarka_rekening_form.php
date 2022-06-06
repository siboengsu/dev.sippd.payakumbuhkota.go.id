<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-rekening-form">
	<div class="col-md-12">
		<form class="form-horizontal form-rekening">
		<input type="hidden" value="1" class="page">
		<input type="hidden" name="l-unitkey" value="<?php echo $unitkey; ?>">
		<input type="hidden" name="l-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Kode</label>
			<div class="col-sm-5">
				<input type="text" name="l-kdper" value="" class="form-control">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Uraian</label>
			<div class="col-sm-10">
				<input type="text" name="l-nmper" value="" class="form-control">
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
			</div>
		</div>
		</form>
	</div>
	
	<form class="form-add">
	<input type="hidden" name="i-unitkey" value="<?php echo $unitkey; ?>">
	<input type="hidden" name="i-kegrkpdkey" value="<?php echo $kegrkpdkey; ?>">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th class="text-center w1px">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" class="check-all">
					<label></label>
				</div>
			</th>
			<th>Kode</th>
			<th>Uraian</th>
		</tr>
		<tbody class="data-load">
			<?php echo $rekening; ?>
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
var blockRekeningForm = '.block-rekening-form ';

function dataLoadRekeningForm() {
	var page = $(blockRekeningForm + '.page').val();
	$.post('/prarka/rekening_form_load/' + page, $(blockRekeningForm + '.form-rekening').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockRekeningForm + '.data-load').html(res);
		}
	});
}

$(function() {
	$(document).off('click', blockRekeningForm + '.check-all');
	$(document).on('click', blockRekeningForm + '.check-all', function(e) {
		var checkboxes = $(blockRekeningForm + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});
	
	$(document).off('submit', blockRekeningForm + '.form-rekening');
	$(document).on('submit', blockRekeningForm + '.form-rekening', function(e) {
		e.preventDefault();
		dataLoadRekeningForm();
		return false;
	});
	
	$(document).off('click', blockRekeningForm + '.btn-page');
	$(document).on('click', blockRekeningForm + '.btn-page', function(e) {
		e.preventDefault();
		$(blockRekeningForm + '.page').val($(this).data('ci-pagination-page'));
		dataLoadRekeningForm();
		return false;
	});
	
	$(document).off('submit', blockRekeningForm + '.form-add');
	$(document).on('submit', blockRekeningForm + '.form-add', function(e) {
		e.preventDefault();
		if($(blockRekeningForm + ".form-add input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		
		$.post('/prarka/rekening_save/', $(blockRekeningForm + '.form-add').serializeArray(), function(res, status, xhr) {
			if(contype(xhr) == 'json') {
				respond(res);
			} else {
				dataLoadRekening();
				modalRekeningForm.close();
			}
		});
		
		return false;
	});
});
</script>

