<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1> Data Master SSH</h1>
		</div>
	</div>
</div>

<div class="row block-entry-ssh">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">SSH</div>
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
								<option value="1">KODE SSH</option>
								<option value="2">KODE REKENING</option>
								<option value="3">NAMA</option>
								<option value="4">SATUAN</option>
								<option value="5">HARGA</option>
								<option value="6">SPESIFIKASI</option>
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
				<th class="text-center">Kode</th>
				<th class="text-center">Nama</th>
				<th class="text-center">Spesifikasi</th>
				<th class="text-center">Satuan</th>
				<th class="text-center">Harga Satuan</th>
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-ssh-tambah <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-ssh-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>


<script>

var blockSSH = '.block-entry-ssh ';

function dataLoadMasterSSH() {
	updateMask(blockSSH);
	var page = $(blockSSH + '.page').val();
	$.post('/master/sshmaster_load/' + page, $(blockSSH + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockSSH + '.data-load').html(res);
		}
	});
}

window.onload=dataLoadMasterSSH();

$(function() {
	
	updateMask(blockSSH);
	
	$(document).off('click', blockSSH + '.btn-ssh-tambah');
	$(document).on('click', blockSSH + '.btn-ssh-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Entry Data SSH';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Data SSH';
			type = 'type-warning';
			data = { 'i-kdssh'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			console.log(data);
			}
		modalEntrySSHForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/master/ssh_form/' + act, data)
		});
		modalEntrySSHForm.open();

		return false;
	});

	$(document).off('click', blockSSH + '.btn-page');
	$(document).on('click', blockSSH + '.btn-page', function(e) {
		e.preventDefault();
		$(blockSSH + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterSSH();
		return false;
	});

	$(document).off('submit', blockSSH + '.form-load');
	$(document).on('submit', blockSSH + '.form-load', function(e) {
		e.preventDefault();
		$(blockSSH + '.page').val('1');
		dataLoadMasterSSH();
		return false;
	});
	
	$(document).off('click', blockSSH + '.btn-ssh-delete');
	$(document).on('click', blockSSH + '.btn-ssh-delete', function(e) {
		e.preventDefault();
		if($(blockSSH + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'are you really want to delete this user?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockSSH + '.form-delete').serializeObject()
						
					);
					$.post('/master/ssh_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadMasterSSH();
						}
					});
				}
			}
		});
		return false;
	});

});
</script>
