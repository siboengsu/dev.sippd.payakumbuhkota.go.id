<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-prioritas-provinsi">
	<div class="col-md-12">

<div class="panel panel-primary">
	<div class="panel-heading text-center text-bold">Prioritas Provinsi</div>
	<div class="panel-body">
	<form class="form-add form-delete">
			<input type="hidden" id="i-unitkey" name="f-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="f-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Nomor</th>
			<th>Uraian Prioritas Provinsi</th>
			<th class="w1px va-mid">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" class="check-all">
					<label></label>
				</div>
			</th>
		</tr>
		<tbody class="data-load">
			<?php echo $prioritas_provinsi; ?>
		</tbody>
		</table>
		</div>
	</div>

		</form>
		<div class="text-center block-pagination"></div>

	<div class="panel-footer">
		<div class="row">
			<div class="col-xs-6"><button type="button" class="btn btn-primary btn-tambah-prioritas-provinsi-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
			<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-tambah-prioritas-provinsi-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
		</div>
	</div>
	</div>
</div>
</div>

	</div>



<div class="row" id="lookup-sasaran-provinsi" style="display:none;">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Sasaran provinsi</div>
			<div class="panel-body">

	<form class="form-add form-load form-delete">
			<input type="hidden" id="i-unitkey" name="f-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="f-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
				<input type="hidden" id="f-prioprovkey" name="f-prioprovkey">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Nomor</th>
			<th>Uraian Sasaran Provinsi</th>
			<th class="w1px va-mid">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" class="check-all">
					<label></label>
				</div>
			</th>
		</tr>
		<tbody class="data-load">

		</tbody>
		</table>
		</div>
	</div>

	<div class="panel-footer">
		<div class="row">
			<div class="col-xs-6"><button type="button" class="btn btn-primary btn-tambah-sasaran-provinsi-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
			<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-tambah-sasaran-provinsi-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
		</div>
	</div>
		</form>
	</div>
</div>
</div>
<div class="panel-footer">
	<div class="row">
		<div class="col-xs-6"><button type="button" class="btn btn-success btn-ok"><i class="fa fa-download"></i> Selesai</button></div>
		</div>
</div>
	</div>


</div>

<script>
var blockLookupPrioritasProvinsi = '#lookup-prioritas-provinsi ',
blockSasaranProvinsi = '#lookup-sasaran-provinsi ';


function dataLoadLookupPrioritasProvinsi() {
		$(blockSasaranProvinsi).hide();
	$.post('/renja/prioritas_form_provinsi_load/', $(blockLookupPrioritasProvinsi + '.form-add').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {

			$(blockLookupPrioritasProvinsi + '.data-load').html(res);
		}
	});
}

function dataLoadSasaranProvinsi() {

		data = $.extend({},
			$(blockSasaranProvinsi + '.form-load').serializeObject(),
			{'f-unitkey' : getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey') }
		);

	$.post('/renja/sasaran_form_provinsi_load/', data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockSasaranProvinsi + '.data-load').html(res);
		}
	});
}




$(document).off('click', blockLookupPrioritasProvinsi + '.btn-tambah-prioritas-provinsi-form');
$(document).on('click', blockLookupPrioritasProvinsi + '.btn-tambah-prioritas-provinsi-form', function(e) {
	e.preventDefault();
	if(isEmpty(getVal('#f-unitkey'))) return false;
	var act = $(this).data('act'),
		data, title, type;

	if(act == 'add') {
		title = 'Tambah Prioritas Provinsi';
		type = 'type-success';
		data = {'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')};
	}

	modalPrioritasProvinsiForm = new BootstrapDialog({
		title: title,
		type: type,
		size: 'size-wide',
		message: $('<div></div>').load('/lookup/prioritas_provinsi/' + act, data)
	});
	modalPrioritasProvinsiForm.open();

	return false;
});

$(function() {

	$(document).off('click', blockSasaranProvinsi + '.btn-ok');
	$(document).on('click', blockSasaranProvinsi + '.btn-ok', function(e) {
		e.preventDefault();
	modalLookupPrioritasProvinsi.close();
	});


	$(document).off('submit', blockLookupPrioritasProvinsi + '.form-load-lookup');
	$(document).on('submit', blockLookupPrioritasProvinsi + '.form-load-lookup', function(e) {
		e.preventDefault();
		dataLoadLookupPrioritasProvinsi();
		return false;
	});

	$(document).off('click', blockLookupPrioritasProvinsi + '.btn-prioritas-show-sasaran');
	$(document).on('click', blockLookupPrioritasProvinsi + '.btn-prioritas-show-sasaran', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockLookupPrioritasProvinsi + '.btn-prioritas-show-sasaran').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-prioprovkey').val(id);
		$(blockSasaranProvinsi).fadeIn('fast');
		dataLoadSasaranProvinsi();
	});



	$(document).off('click', blockSasaranProvinsi + '.btn-tambah-sasaran-provinsi-form');
	$(document).on('click', blockSasaranProvinsi + '.btn-tambah-sasaran-provinsi-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Tambah Sasaran Provinsi';
			type = 'type-success';
			data = {'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey'),'f-prioprovkey'	: getVal('#f-prioprovkey')};

		}
		modalSasaranProvForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/sasaran_provinsi/' + act, data)
		});
		modalSasaranProvForm.open();

		return false;
	});


	$(document).off('click', blockLookupPrioritasProvinsi + '.check-all');
	$(document).on('click', blockLookupPrioritasProvinsi + '.check-all', function(e) {
		var checkboxes = $(blockLookupPrioritasProvinsi + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});


	$(document).off('click', blockSasaranProvinsi + '.check-all');
	$(document).on('click', blockSasaranProvinsi + '.check-all', function(e) {
		var checkboxes = $(blockSasaranProvinsi + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});


		$(document).off('click', blockLookupPrioritasProvinsi + '.btn-tambah-prioritas-provinsi-delete');
		$(document).on('click', blockLookupPrioritasProvinsi + '.btn-tambah-prioritas-provinsi-delete', function(e) {
			e.preventDefault();

		if($(blockLookupPrioritasProvinsi + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar prioritas Provinsi yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockLookupPrioritasProvinsi + '.form-delete').serializeObject(),
						{
							'i-unitkey'		: getVal('#i-unitkey'),
							'i-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')
						}
					);
					$.post('/renja/prioritas_delete_provinsi/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadLookupPrioritasProvinsi();
						}
					});
				}
			}
		});

		return false;
	});


	$(document).off('click', blockSasaranProvinsi + '.btn-tambah-sasaran-provinsi-delete');
	$(document).on('click', blockSasaranProvinsi + '.btn-tambah-sasaran-provinsi-delete', function(e) {
		e.preventDefault();

	if(isEmpty(getVal('#i-unitkey'))) return false;
	if(isEmpty(getVal('#i-pgrmrkpdkey'))) return false;
	if(isEmpty(getVal('#f-prioprovkey'))) return false;
	if($(blockSasaranProvinsi + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
		return false;
	}
	var id = $(this).closest('tr').data('id');
	goConfirm({
		msg : 'Hapus daftar Sasaran Provinsi yang dipilih ?',
		type: 'danger',
		callback : function(ok) {
			if(ok) {
				var data = $.extend({},
					$(blockSasaranProvinsi + '.form-delete').serializeObject(),
					{
						'i-unitkey'		: getVal('#i-unitkey'),
						'i-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey'),
						'f-prioprovkey'	: getVal('#f-prioprovkey')
					}
				);
				$.post('/renja/sasaran_delete_provinsi/', data, function(res, status, xhr) {
					if(contype(xhr) == 'json') {
						respond(res);
					} else {
						dataLoadSasaranProvinsi();
					}
				});
			}
		}
	});

	return false;
});

$(document).off('click', blockSasaranProvinsi + '.btn-ok');
$(document).on('click', blockSasaranProvinsi + '.btn-ok', function(e) {
	e.preventDefault();

modalLookupPrioritasProvinsi.close();
});



});
</script>
