<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="prioritas-kota" id="PrioritasKota">
<div class="row" id="lookup-prioritas">
		<div class="col-md-12">

			<div class="panel panel-primary">
	<div class="panel-heading text-center text-bold">Prioritas Daerah</div>
	<div class="panel-body">
	<form class="form-add form-delete">
			<input type="hidden" id="i-unitkey" name="f-unitkey" value="<?php echo $unitkey; ?>">
			<input type="hidden" id="i-pgrmrkpdkey" name="f-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
	<div class="col-md-12">
		<div class="table-responsive">
		<table class="table table-striped table-condensed table-bordered">
		<tr>
			<th>Nomor</th>
			<th>Uraian Prioritas</th>
			<th class="w1px va-mid">
				<div class="checkbox checkbox-inline">
					<input type="checkbox" class="check-all">
					<label></label>
				</div>
			</th>
		</tr>
		<tbody class="data-load">
			<?php echo $prioritas; ?>
		</tbody>
		</table>
		</div>
	</div>

		</form>
		<div class="text-center block-pagination"></div>

	<div class="panel-footer">
		<div class="row">
			<div class="col-xs-6"><button type="button" class="btn btn-primary btn-tambah-prioritas-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
			<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-tambah-prioritas-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
		</div>
	</div>
	</div>
</div>
		</div>

</div>



<div class="row" id="lookup-sasaran" style="display:none;">
			<div class="col-md-12">
						<div class="panel panel-primary">
							<div class="panel-heading text-center text-bold">Sasaran Daerah</div>
							<div class="panel-body">

					<form class="form-add form-load form-delete">
							<input type="hidden" id="i-unitkey" name="f-unitkey" value="<?php echo $unitkey; ?>">
							<input type="hidden" id="i-pgrmrkpdkey" name="f-pgrmrkpdkey" value="<?php echo $pgrmrkpdkey; ?>">
								<input type="hidden" id="f-prioppaskey" name="f-prioppaskey">
					<div class="col-md-12">
						<div class="table-responsive">
						<table class="table table-striped table-condensed table-bordered">
						<tr>
							<th>Nomor</th>
							<th>Uraian Sasaran</th>
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
							<div class="col-xs-6"><button type="button" class="btn btn-primary btn-tambah-sasaran-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
							<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-tambah-sasaran-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
						</div>
					</div>
						</form>
					</div>
				</div>
		</div>

		<div class="panel-footer">
			<div class="row">
				<div class="col-xs-6"><button type="submit" class="btn btn-success btn-ok"><i class="fa fa-download"></i> Selesai</button></div>
				</div>
		</div>
</div>


</div>

<script>

var blockLookupPrioritas = '#lookup-prioritas ',
prioritasDaerah = '#PrioritasKota ',
blockSasaran = '#lookup-sasaran ';


function dataLoadLookupPrioritas() {
		$(blockSasaran).hide();
	$.post('/renja/prioritas_form_load/', $(blockLookupPrioritas + '.form-add').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {

			$(blockLookupPrioritas + '.data-load').html(res);
		}
	});
}

function dataLoadSasaran() {

		data = $.extend({},
			$(blockSasaran + '.form-load').serializeObject(),
			{'f-unitkey' : getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey') }
		);

	$.post('/renja/sasaran_form_load/', data, function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockSasaran + '.data-load').html(res);
		}
	});
}






$(document).off('click', blockLookupPrioritas + '.btn-tambah-prioritas-form');
$(document).on('click', blockLookupPrioritas + '.btn-tambah-prioritas-form', function(e) {
	e.preventDefault();
	if(isEmpty(getVal('#f-unitkey'))) return false;
	var act = $(this).data('act'),
		data, title, type;

	if(act == 'add') {
		title = 'Tambah Prioritas';
		type = 'type-success';
		data = {'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')};
	}

	modalPrioritasForm = new BootstrapDialog({
		title: title,
		type: type,
		size: 'size-wide',
		message: $('<div></div>').load('/lookup/prioritas/' + act, data)
	});
	modalPrioritasForm.open();

	return false;
});

$(function() {



	$(document).off('submit', blockLookupPrioritas + '.form-load-lookup');
	$(document).on('submit', blockLookupPrioritas + '.form-load-lookup', function(e) {
		e.preventDefault();
		dataLoadLookupPrioritas();
		return false;
	});

	$(document).off('click', blockLookupPrioritas + '.btn-prioritas-show-sasaran');
	$(document).on('click', blockLookupPrioritas + '.btn-prioritas-show-sasaran', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockLookupPrioritas + '.btn-prioritas-show-sasaran').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-prioppaskey').val(id);
		$(blockSasaran).fadeIn('fast');
		dataLoadSasaran();
	});

	$(document).off('click', blockSasaran + '.btn-ok');
	$(document).on('click', blockSasaran + '.btn-ok', function(e) {
		e.preventDefault();

	modalLookupPrioritas.close();
	});




	$(document).off('click', blockSasaran + '.btn-tambah-sasaran-form');
	$(document).on('click', blockSasaran + '.btn-tambah-sasaran-form', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Tambah Sasaran';
			type = 'type-success';
			data = {'f-unitkey'	: getVal('#i-unitkey'),'f-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey'),'f-prioppaskey'	: getVal('#f-prioppaskey')};
		}
		modalSasaranForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/lookup/sasaran/' + act, data)
		});
		modalSasaranForm.open();

		return false;
	});






	$(document).off('click', blockLookupPrioritas + '.check-all');
	$(document).on('click', blockLookupPrioritas + '.check-all', function(e) {
		var checkboxes = $(blockLookupPrioritas + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});


	$(document).off('click', blockSasaran + '.check-all');
	$(document).on('click', blockSasaran + '.check-all', function(e) {
		var checkboxes = $(blockSasaran + "input[name='i-check[]']:checkbox");
		checkboxes.prop('checked', $(this).is(':checked')).not($(this)).change();
	});


		$(document).off('click', blockLookupPrioritas + '.btn-tambah-prioritas-delete');
		$(document).on('click', blockLookupPrioritas + '.btn-tambah-prioritas-delete', function(e) {
			e.preventDefault();

		if($(blockLookupPrioritas + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar prioritas yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockLookupPrioritas + '.form-delete').serializeObject(),
						{
							'i-unitkey'		: getVal('#i-unitkey'),
							'i-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey')
						}
					);
					$.post('/renja/prioritas_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadLookupPrioritas();
						}
					});
				}
			}
		});

		return false;
	});


	$(document).off('click', blockSasaran + '.btn-tambah-sasaran-delete');
	$(document).on('click', blockSasaran + '.btn-tambah-sasaran-delete', function(e) {
		e.preventDefault();

	if(isEmpty(getVal('#i-unitkey'))) return false;
	if(isEmpty(getVal('#i-pgrmrkpdkey'))) return false;
	if(isEmpty(getVal('#f-prioppaskey'))) return false;
	if($(blockSasaran + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
		return false;
	}
	var id = $(this).closest('tr').data('id');
	goConfirm({
		msg : 'Hapus daftar Sasaran yang dipilih ?',
		type: 'danger',
		callback : function(ok) {
			if(ok) {
				var data = $.extend({},
					$(blockSasaran + '.form-delete').serializeObject(),
					{
						'i-unitkey'		: getVal('#i-unitkey'),
						'i-pgrmrkpdkey'	: getVal('#i-pgrmrkpdkey'),
						'f-prioppaskey'	: getVal('#f-prioppaskey')
					}
				);
				$.post('/renja/sasaran_delete/', data, function(res, status, xhr) {
					if(contype(xhr) == 'json') {
						respond(res);
					} else {
						dataLoadSasaran();
					}
				});
			}
		}
	});

	return false;
});



});
</script>
