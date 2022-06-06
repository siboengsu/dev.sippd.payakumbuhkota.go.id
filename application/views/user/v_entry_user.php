<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1> User SIPPD</h1>
		</div>
	</div>
</div>

<div class="row block-entry-user">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Daftar User</div>
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
									<option value="1">Nama OPD</option>
									<option value="2">Username</option>
									<option value="3">NIP</option>
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
									<th class="text-center table-tr-header">Nama OPD</th>
									<th class="text-center table-tr-header">Username</th>
									<th class="text-center table-tr-header">NIP</th>
									<th class="text-center table-tr-header">Edit</th>
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
				</div>
				<div class="text-center block-pagination"></div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-xs-6"><button type="button" class="btn btn-primary btn-user-tambah <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
						<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-user-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

var blockuser = '.block-entry-user ';

function dataLoadUser() {
	updateMask(blockuser);
	var page = $(blockuser + '.page').val();
	$.post('/user/user_load/' + page, $(blockuser + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockuser + '.data-load').html(res);
		}
	});
}

window.onload=dataLoadUser();

$(function() {

	updateMask(blockuser);

	$(document).off('click', blockuser + '.btn-user-tambah');
	$(document).on('click', blockuser + '.btn-user-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;
		if(act == 'add') {
			title = 'Entry Data User';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Data User';
			type = 'type-warning';
			data = { 'i-userid'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			}
			modaluserform = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/user/user_form/' + act, data)
		});
		modaluserform.open();
		return false;
	});

	$(document).off('click', blockuser + '.btn-page');
	$(document).on('click', blockuser + '.btn-page', function(e) {
		e.preventDefault();
		$(blockuser + '.page').val($(this).data('ci-pagination-page'));
		dataLoadUser();
		return false;
	});

	$(document).off('submit', blockuser + '.form-load');
	$(document).on('submit', blockuser + '.form-load', function(e) {
		e.preventDefault();
		$(blockuser + '.page').val('1');
		dataLoadUser();
		return false;
	});

	$(document).off('click', blockuser + '.btn-user-delete');
	$(document).on('click', blockuser + '.btn-user-delete', function(e) {
		e.preventDefault();
		if($(blockuser + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus user yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockuser + '.form-delete').serializeObject()

					);
					$.post('/user/user_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadUser();
						}
					});
				}
			}
		});

		return false;
	});

});
</script>
