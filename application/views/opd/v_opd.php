
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Kepala OPD</h1>
		</div>
	</div>
</div>

<div class="row block-entry-opd">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Daftar Kepala OPD</div>
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
									<option value="3">Perangkat Daerah</option>
									<option value="2">Nama</option>
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
									<th class="text-center table-tr-header">OPD</th>
                                    <th class="text-center table-tr-header">Nama</th>
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
						<div class="col-xs-6"><button type="button" class="btn btn-primary btn-opd-tambah <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
						<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-opd-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	
<script>
var blockopd = ".block-entry-opd ";

function dataLoadOpd() {
	updateMask(blockopd);
	var page = $(blockopd + '.page').val();
	$.post('/opd/opd_load/' + page, $(blockopd + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockopd + '.data-load').html(res);
		}
	});
}

window.onload=dataLoadOpd();

$(function() {
	updateMask(blockopd);
	$(document).off('click', blockopd + '.btn-opd-tambah');
	$(document).on('click', blockopd + '.btn-opd-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;
		if(act == 'add') {
			title = 'Entry Data Kepala OPD';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Data Kepala OPD';
			type = 'type-warning';
			data = { 'i-id'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			}
			modalopdform = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/opd/opd_form/' + act, data)
		});
		modalopdform.open();
		return false;
	});
});

$(document).off('click', blockopd + '.btn-page');
$(document).on('click', blockopd + '.btn-page', function(e) {
	e.preventDefault();
	$(blockopd + '.page').val($(this).data('ci-pagination-page'));
	dataLoadOpd();
	return false;
});

$(document).off('submit', blockopd + '.form-load');
$(document).on('submit', blockopd + '.form-load', function(e) {
	e.preventDefault();
	$(blockopd + '.page').val('1');
	dataLoadOpd();
	return false;
});

$(document).off('click', blockopd + '.btn-opd-delete');
$(document).on('click', blockopd + '.btn-opd-delete', function(e) {
	e.preventDefault();
	if($(blockopd + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
		return false;
	}
	var id = $(this).closest('tr').data('id');
	goConfirm({
		msg : 'Hapus daftar Kepala Daerah ?',
		type: 'danger',
		callback : function(ok) {
			if(ok) {
				var data = $.extend({},
					$(blockopd + '.form-delete').serializeObject()
				);
				$.post('/opd/opd_delete/', data, function(res, status, xhr) {
					if(contype(xhr) == 'json') {
						respond(res);
					} else {
						dataLoadOpd();
					}
				});
			}
		}
	});
	return false;
});
</script>
