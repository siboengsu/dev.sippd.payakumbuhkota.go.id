<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1> Data Master PEMDA</h1>
		</div>
	</div>
</div>

<div class="row block-entry-pemda">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">PEMDA</div>
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
								<option value="1">NILAI</option>

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
				<th class="text-center">Uraian</th>
				<th class="text-center">Nilai</th>
				<th class="text-center text-nowrap">Edit</th>
			</tr>
			<tbody class="data-load"></tbody>
			</table>
			</div>
			</form>

			<div class="text-center block-pagination"></div>
		</div>
	</div>
</div>


<script>

var blockPemda = '.block-entry-pemda ';

function dataLoadMasterPemda() {
	var page = $(blockPemda + '.page').val();
	$.post('/master/pemdamaster_load/' + page, $(blockPemda + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockPemda + '.data-load').html(res);
		}
	});
}

window.onload=dataLoadMasterPemda();

$(function() {
	$(document).off('click', blockPemda + '.btn-pemda-tambah');
	$(document).on('click', blockPemda + '.btn-pemda-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Entry Data Pemda';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Data Pemda';
			type = 'type-warning';
			data = { 'f-configid'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			}

		modalEntryPemdaForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/master/pemda_form/' + act, data)
		});
		modalEntryPemdaForm.open();

		return false;
	});

	$(document).off('click', blockPemda + '.btn-page');
	$(document).on('click', blockPemda + '.btn-page', function(e) {
		e.preventDefault();
		$(blockPemda + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterPemda();
		return false;
	});

	$(document).off('submit', blockPemda + '.form-load');
	$(document).on('submit', blockPemda + '.form-load', function(e) {
		e.preventDefault();
		$(blockPemda + '.page').val('1');
		dataLoadMasterPemda();
		return false;
	});



});
</script>
