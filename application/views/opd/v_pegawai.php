
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row block-entry-pegawai">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Daftar Pegawai</div>
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
									<option value="1">NIP</option>
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
									<th class="text-center table-tr-header">Aksi</th>
									<th class="text-center table-tr-header">NIP</th>
									<th class="text-center table-tr-header">Nama</th>
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
						<div class="col-xs-6"><button type="button" class="btn btn-primary btn-pegawai-tambah <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
						<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-pegawai-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
var blockpegawai = ".block-entry-pegawai ";

function dataLoadPegawai() {
	updateMask(blockpegawai);
	var page = $(blockpegawai + '.page').val();
	$.post('/opd/pegawai_load/' + page, $(blockpegawai + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockpegawai + '.data-load').html(res);
		}
	});
}

window.onload=dataLoadPegawai();

$(function() {
	updateMask(blockpegawai);
	$(document).off('click', blockpegawai + '.btn-pegawai-tambah');
	$(document).on('click', blockpegawai + '.btn-pegawai-tambah', function(e) {
		e.preventDefault();
		var act = $(this).data('act'),
			data, title, type;
		if(act == 'add') {
			title = 'Entry Data Pegawai';
			type = 'type-success';

		} else if(act == 'edit') {
			title = 'Edit Data Pegawai';
			type = 'type-warning';
			data = { 'i-nip'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
			}
			modalpegawaiform = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/opd/pegawai_form/' + act, data)
		});
		modalpegawaiform.open();
		return false;
	});
});

$(document).off('click', blockpegawai + '.btn-page');
$(document).on('click', blockpegawai + '.btn-page', function(e) {
	e.preventDefault();
	$(blockpegawai + '.page').val($(this).data('ci-pagination-page'));
	dataLoadPegawai();
	return false;
});

$(document).off('submit', blockpegawai + '.form-load');
$(document).on('submit', blockpegawai + '.form-load', function(e) {
	e.preventDefault();
	$(blockpegawai + '.page').val('1');
	dataLoadPegawai();
	return false;
});

$(document).off('click', blockpegawai + '.btn-pegawai-delete');
$(document).on('click', blockpegawai + '.btn-pegawai-delete', function(e) {
	e.preventDefault();
	if($(blockpegawai + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
		return false;
	}
	var id = $(this).closest('tr').data('id');
	goConfirm({
		msg : 'Hapus daftar Kepala Daerah ?',
		type: 'danger',
		callback : function(ok) {
			if(ok) {
				var data = $.extend({},
					$(blockpegawai + '.form-delete').serializeObject()
				);
				$.post('/opd/pegawai_delete/', data, function(res, status, xhr) {
					if(contype(xhr) == 'json') {
						respond(res);
					} else {
						dataLoadPegawai();
					}
				});
			}
		}
	});
	return false;
});

$(function() {
	$(document).off('click', blockpegawai + '.btn-select');
	$(document).on('click', blockpegawai + '.btn-select', function(e) {
		e.preventDefault();
		var tr = $(this).closest('tr');
		var setid = tr.find('td:eq(1)').text(),
			setnm = tr.find('td:eq(2)').text();
			
		$('<?php echo $setid; ?>').val(setid.trim()).change();
		$('<?php echo $setnm; ?>').val(setnm.trim()).change();
		
		modalLookupPegawai.close();
		return false;
	});
});
</script>
