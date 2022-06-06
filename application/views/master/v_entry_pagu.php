<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Entry Data Master Pagu</h1>
		</div>
	</div>
</div>

<div class="row block-entry-pagu">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">PAGU SKPD</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<input type="hidden" value="1" class="page">

					<div class="form-group">
						<label class="col-sm-1 control-label" style="text-align:center;padding-top:10px;">Pencarian</label>
						<div class="col-sm-2">
							<input type="text" name="f-search_key" class="form-control">
						</div>

						<div class="form-gap visible-xs-block"></div>

						<div class="col-sm-1">
							<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" style="display:block !important;">
								<option value="1">Kode</option>
								<option value="2">Nama Unit</option>
							</select>
						</div>

						<div class="col-sm-2">
							<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
						</div>



					</div>
				</form>


			<form class="form-delete form-load">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr style="background-color: #d5d8da;">
				<th class="text-center ">Kode</th>
					<th class="text-center text-nowrap">Uraian</th>
				<th class="text-center w2px">Nilai</th>
				<th class="text-center text-nowrap">Edit</th>
				<th class="w1px">
					<div class="checkbox checkbox-inline">
						<input type="checkbox" class="check-all">
						<label></label>
					</div>
				</th>
			</tr>
			<tbody class="data-load"></tbody>
			</table>
			</div>
			</form>

			<div class="text-center block-pagination"></div>

			<div class="panel-footer">
				<div class="row">
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-pagu-tambah <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-pagu-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>

var blockEntryPagu = '.block-entry-pagu ';

function dataLoadMasterPagu() {
	var page = $(blockEntryPagu + '.page').val();
	$.post('/master/pagumaster_load/' + page, $(blockEntryPagu + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockEntryPagu + '.data-load').html(res);
		}
	});
}

window.onload=dataLoadMasterPagu();

$(function() {
	$(document).off('click', blockEntryPagu + '.btn-pagu-tambah');
	$(document).on('click', blockEntryPagu + '.btn-pagu-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Entry Pagu Baru';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Pagu';
			type = 'type-warning';
			data = { 'f-unitkey'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			}

		modalEntryPaguForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/master/pagu_form/' + act, data)
		});
		modalEntryPaguForm.open();

		return false;
	});

	$(document).off('click', blockEntryPagu + '.btn-page');
	$(document).on('click', blockEntryPagu + '.btn-page', function(e) {
		e.preventDefault();
		$(blockEntryPagu + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterPagu();
		return false;
	});

	$(document).off('submit', blockEntryPagu + '.form-load');
	$(document).on('submit', blockEntryPagu + '.form-load', function(e) {
		e.preventDefault();
		$(blockEntryPagu + '.page').val('1');
		dataLoadMasterPagu


		();
		return false;
	});
	
	
		$(document).off('click', blockEntryPagu + '.check-all');
	$(document).on('click', blockEntryPagu + '.check-all', function(e) {
		var checkboxes = $(blockEntryPagu + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});
	
	

	$(document).off('click', blockEntryPagu + '.btn-pagu-delete');
	$(document).on('click', blockEntryPagu + '.btn-pagu-delete', function(e) {
		e.preventDefault();
			if($(blockEntryPagu + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar Pagu OPD yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockEntryPagu + '.form-delete').serializeObject()

					);
					$.post('/master/pagu_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadMasterPagu();
						}
					});
				}
			}
		});

		return false;
	});


});
</script>
