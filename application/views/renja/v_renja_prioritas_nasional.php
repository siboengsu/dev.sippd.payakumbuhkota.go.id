<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row" id="lookup-prioritas-nasional">
	<div class="col-md-12">

<div class="panel panel-primary">
	<div class="panel-heading text-center text-bold">Prioritas Nasional</div>
	<div class="panel-body">
	<form class="form-add form-delete">
			<input type="hidden" id="i-unitkey" name="f-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="f-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Nomor</th>
			<th>Uraian Prioritas Nasional</th>
			<th class="w1px va-mid">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" class="check-all">
					<label></label>
				</div>
			</th>
		</tr>
		<tbody class="data-load">
			<?php echo $prioritas_nasional; ?>
		</tbody>
		</table>
		</div>
	</div>

		</form>
		<div class="text-center block-pagination"></div>

	<div class="panel-footer">
		<div class="row">
			<div class="col-xs-6"><button type="button" class="btn btn-primary btn-tambah-prioritas-nasional-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
			<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-tambah-prioritas-nasional-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
		</div>
	</div>
	</div>
</div>
</div>

	</div>



<div class="row" id="lookup-sasaran-nasional" style="display:none;">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Sasaran Nasional</div>
			<div class="panel-body">

	<form class="form-add form-load form-delete">
			<input type="hidden" id="i-unitkey" name="f-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="f-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
				<input type="hidden" id="f-prionaskey" name="f-prionaskey">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Nomor</th>
			<th>Uraian Sasaran Nasional</th>
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
			<div class="col-xs-6"><button type="button" class="btn btn-primary btn-tambah-sasaran-nasional-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
			<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-tambah-sasaran-nasional-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
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
var blockLookupPrioritasNasional = '#lookup-prioritas-nasional ',
blockSasaranNasional = '#lookup-sasaran-nasional ';


function dataLoadLookupPrioritasNasional() {
		$(blockSasaranNasional).hide();
	$.post('/renja/prioritas_form_nasional_load/', $(blockLookupPrioritasNasional + '.form-add').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {

			$(blockLookupPrioritasNasional + '.data-load').html(res);
		}
	});
}

function dataLoadSasaranNasional() {

		data = $.extend({},
			$(blockSasaranNasional + '.form-load').serializeObject(),
			{'f-unitkey' : getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey') }
		);

	$.post('/renja/sasaran_form_nasional_load/', data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockSasaranNasional + '.data-load').html(res);
		}
	});
}


$(function() {

	$(document).off('click', blockLookupPrioritasNasional + '.btn-tambah-prioritas-nasional-form');
	$(document).on('click', blockLookupPrioritasNasional + '.btn-tambah-prioritas-nasional-form', function(e) {
		e.preventDefault();

		if(isEmpty(getVal('#f-unitkey'))) return false;
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Tambah Prioritas Nasional';
			type = 'type-success';
			data = {'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')};
		}

		modalPrioritasNasionalForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/prioritas_nasional/' + act, data)
		});
		modalPrioritasNasionalForm.open();

		return false;
	});

	$(document).off('click', blockSasaranNasional + '.btn-ok');
	$(document).on('click', blockSasaranNasional + '.btn-ok', function(e) {
		e.preventDefault();
	modalLookupPrioritasNasional.close();
	});


	$(document).off('submit', blockLookupPrioritasNasional + '.form-load-lookup');
	$(document).on('submit', blockLookupPrioritasNasional + '.form-load-lookup', function(e) {
		e.preventDefault();
		dataLoadLookupPrioritasNasional();
		return false;
	});

	$(document).off('click', blockLookupPrioritasNasional + '.btn-prioritas-show-sasaran');
	$(document).on('click', blockLookupPrioritasNasional + '.btn-prioritas-show-sasaran', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockLookupPrioritasNasional + '.btn-prioritas-show-sasaran').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-prionaskey').val(id);
		$(blockSasaranNasional).fadeIn('fast');
		dataLoadSasaranNasional();
	});



	$(document).off('click', blockSasaranNasional + '.btn-tambah-sasaran-nasional-form');
	$(document).on('click', blockSasaranNasional + '.btn-tambah-sasaran-nasional-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Tambah Sasaran Nasional';
			type = 'type-success';
			data = {'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey'),'f-prionaskey'	: getVal('#f-prionaskey')};

		}
		modalSasaranNasForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/sasaran_Nasional/' + act, data)
		});
		modalSasaranNasForm.open();

		return false;
	});


	$(document).off('click', blockLookupPrioritasNasional + '.check-all');
	$(document).on('click', blockLookupPrioritasNasional + '.check-all', function(e) {
		var checkboxes = $(blockLookupPrioritasNasional + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});


	$(document).off('click', blockSasaranNasional + '.check-all');
	$(document).on('click', blockSasaranNasional + '.check-all', function(e) {
		var checkboxes = $(blockSasaranNasional + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});


		$(document).off('click', blockLookupPrioritasNasional + '.btn-tambah-prioritas-nasional-delete');
		$(document).on('click', blockLookupPrioritasNasional + '.btn-tambah-prioritas-nasional-delete', function(e) {
			e.preventDefault();

		if($(blockLookupPrioritasNasional + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar prioritas Nasional yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockLookupPrioritasNasional + '.form-delete').serializeObject(),
						{
							'i-unitkey'		: getVal('#i-unitkey'),
							'i-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')
						}
					);
					$.post('/renja/prioritas_delete_nasional/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadLookupPrioritasNasional();
						}
					});
				}
			}
		});

		return false;
	});


	$(document).off('click', blockSasaranNasional + '.btn-tambah-sasaran-nasional-delete');
	$(document).on('click', blockSasaranNasional + '.btn-tambah-sasaran-nasional-delete', function(e) {
		e.preventDefault();

	if(isEmpty(getVal('#i-unitkey'))) return false;
	if(isEmpty(getVal('#i-pgrmrkpdkey'))) return false;
	if(isEmpty(getVal('#f-prionaskey'))) return false;
	if($(blockSasaranNasional + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
		return false;
	}
	var id = $(this).closest('tr').data('id');
	goConfirm({
		msg : 'Hapus daftar Sasaran Nasional yang dipilih ?',
		type: 'danger',
		callback : function(ok) {
			if(ok) {
				var data = $.extend({},
					$(blockSasaranNasional + '.form-delete').serializeObject(),
					{
						'i-unitkey'		: getVal('#i-unitkey'),
						'i-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey'),
						'f-prionaskey'	: getVal('#f-prionaskey')
					}
				);
				$.post('/renja/sasaran_delete_nasional/', data, function(res, status, xhr) {
					if(contype(xhr) == 'json') {
						respond(res);
					} else {
						dataLoadSasaranNasional();
					}
				});
			}
		}
	});

	return false;
});

$(document).off('click', blockSasaranNasional + '.btn-ok');
$(document).on('click', blockSasaranNasional + '.btn-ok', function(e) {
	e.preventDefault();

modalLookupPrioritasNasional.close();
});



});
</script>
