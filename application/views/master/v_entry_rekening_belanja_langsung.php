<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1> Data Master Rekening Belanja Langsung</h1>
		</div>
	</div>
</div>

<div class="row block-entry-rekening-belanja-langsung">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Rekening Belanja Langsung</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<input type="hidden" value="1" class="page">

					<div class="form-group">
						<label class="col-sm-1 control-label" style="text-align:center;padding-top:10px;">Pencarian</label>
						<div class="col-sm-2">
							<input type="text" name="f-search_key" class="form-control">
						</div>

						<div class="form-gap visible-xs-block"></div>

						<div class="col-sm-2">
							<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto" style="display:block !important;">
								<option value="1">MATANGKEY</option>
								<option value="2">MATANG LEVEL</option>
								<option value="3">KODE REKENING</option>
								<option value="4">NAMA REKENING</option>
								<option value="5">TYPE</option>

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
				<th class="text-center table-tr-header">MATANGKEY</th>
				<th class="text-center">MATANG LEVEL</th>
				<th class="text-center">KODE REKENING</th>
				<th class="text-center">NMPER</th>
				<th class="text-center">TYPE</th>
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-rekening-belanja-langsung-tambah <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-rekening-belanja-langsung-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>

var blockRekeningBelanjaLangsung = '.block-entry-rekening-belanja-langsung ';

function dataLoadMasterRekeningBelanjaLangsung() {
	updateMask(blockRekeningBelanjaLangsung);
	var page = $(blockRekeningBelanjaLangsung + '.page').val();
	$.post('/master/MasterRekeningBelanjaLangsung_load/' + page, $(blockRekeningBelanjaLangsung + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockRekeningBelanjaLangsung + '.data-load').html(res);
		}
	});
}

window.onload=dataLoadMasterRekeningBelanjaLangsung();

$(function() {

	updateMask(blockRekeningBelanjaLangsung);

	$(document).off('click', blockRekeningBelanjaLangsung + '.btn-rekening-belanja-langsung-tambah');
	$(document).on('click', blockRekeningBelanjaLangsung + '.btn-rekening-belanja-langsung-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Entry Data Rekening Belanja Langsung';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Data Rekening Belanja Langsung';
			type = 'type-warning';
			data = { 'i-mtgkey'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			}


		modalRekeningBelanjaLangsungForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/master/MasterRekeningBelanjaLangsung_form/' + act, data)
		});
		modalRekeningBelanjaLangsungForm.open();

		return false;
	});

	$(document).off('click', blockRekeningBelanjaLangsung + '.btn-page');
	$(document).on('click', blockRekeningBelanjaLangsung + '.btn-page', function(e) {
		e.preventDefault();
		$(blockRekeningBelanjaLangsung + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterRekeningBelanjaLangsung();
		return false;
	});

	$(document).off('submit', blockRekeningBelanjaLangsung + '.form-load');
	$(document).on('submit', blockRekeningBelanjaLangsung + '.form-load', function(e) {
		e.preventDefault();
		$(blockRekeningBelanjaLangsung + '.page').val('1');
		dataLoadMasterRekeningBelanjaLangsung();
		return false;
	});

	$(document).off('click', blockRekeningBelanjaLangsung + '.btn-rekening-belanja-langsung-delete');
	$(document).on('click', blockRekeningBelanjaLangsung + '.btn-rekening-belanja-langsung-delete', function(e) {
		e.preventDefault();
		if($(blockRekeningBelanjaLangsung + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar Rekening Belanja Langsung yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockRekeningBelanjaLangsung + '.form-delete').serializeObject()

					);
					$.post('/master/MasterRekeningBelanjaLangsung_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadMasterRekeningBelanjaLangsung();
						}
					});
				}
			}
		});

		return false;
	});



});
</script>
