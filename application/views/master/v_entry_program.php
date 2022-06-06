<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Entry Data Master Program dan Kegiatan</h1>
		</div>
	</div>
</div>

<div class="row block-entry-program">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Unit Organisasi</div>
			<div class="panel-body">
				<form class="form-horizontal form-load">
					<input type="hidden" name="f-unitkey" id="f-unitkey" value="<?php echo $this->session->UNITKEY; ?>" required>
					<input type="hidden" value="1" class="page">

					<div class="form-group">
						<label class="col-sm-2 control-label">Unit Organisasi</label>
						<div class="col-sm-2">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
								<input type="text" value="<?php echo $this->session->KDUNIT; ?>" id="v-kdunit" class="form-control text-bold" placeholder="Kode Unit" readonly>
							</div>
						</div>

						<div class="form-gap visible-xs-block"></div>

						<div class="col-sm-8">
							<?php if($this->sip->is_admin()): ?>
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-lookup-unit" data-setid="#f-unitkey" data-setkd="#v-kdunit" data-setnm="#v-nmunit"><i class="fa fa-folder-open"></i></button>
								</span>
								<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
							</div>
							<?php else: ?>
							<input type="text" id="v-nmunit" value="<?php echo $this->session->NMUNIT; ?>" class="form-control text-bold" placeholder="Nama Unit" readonly>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Pencarian</label>
						<div class="col-sm-2">
							<input type="text" name="f-search_key" class="form-control">
						</div>

						<div class="form-gap visible-xs-block"></div>

						<div class="col-sm-8">
							<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">

								<option value="1">Program Key</option>
								<option value="2">Nama Program</option>

							</select>
							<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">PROGRAM RKPD</div>

			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="text-center w1px">Program Key</th>
					<th class="text-center w1px">No Program</th>
				<th class="text-center text-nowrap">Nama Program</th>
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-program-tambah <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-program-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 block-entry-kegiatan" style="display:none;">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Kegiatan RKPD</div>
			<div class="panel-body">
				<form class="form-inline form-load">
					<input type="hidden" name="f-pgrmrkpdkey" id="f-pgrmrkpdkey">
					<input type="hidden" value="1" class="page">

					<div class="form-group">
						<label class="sr-only"></label>
						<input type="text" name="f-search_key" class="form-control">
					</div>
					<div class="form-group">
						<label class="sr-only"></label>
						<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
							<option value="1">Kegiatan Key</option>
							<option value="2">Nama Kegiatan</option>
						</select>
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
					</div>
				</form>
			</div>

			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="text-center w1px">Kegiatan Key</th>
				<th class="text-center w1px">Nomor Kegiatan</th>
				<th class="text-center text-nowrap">Nama Kegiatan</th>
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-entry-kegiatan-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-entry-kegiatan-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>


</div>

<!-- sub kegiatan -->
<div class="row">
	<div class="col-md-12 block-entry-sub-kegiatan" style="display:none;">
		<div class="panel panel-primary">
			<div class="panel-heading text-center text-bold">Sub Kegiatan RKPD</div>
			<div class="panel-body">
				<form class="form-inline form-load">
					<input type="hidden" name="f-kegrkpdkey" id="f-kegrkpdkey">
					<input type="hidden" value="1" class="page">

					<div class="form-group">
						<label class="sr-only"></label>
						<input type="text" name="f-search_key" class="form-control">
					</div>
					<div class="form-group">
						<label class="sr-only"></label>
						<select name="f-search_type" class="form-control selectpicker show-tick show-menu-arrow" data-width="auto">
							<option value="1">Sub Kegiatan Key</option>
							<option value="2">Nama Sub Kegiatan</option>
						</select>
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
					</div>
				</form>
			</div>

			<form class="form-delete">
			<div class="table-responsive">
			<table class="table table-condensed table-bordered table-striped f12">
			<tr>
				<th class="text-center w1px">Sub Kegiatan Key</th>
				<th class="text-center w1px">Nomor Sub Kegiatan</th>
				<th class="text-center text-nowrap">Nama Sub Kegiatan</th>
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
					<div class="col-xs-6"><button type="button" class="btn btn-primary btn-entry-sub-kegiatan-form <?php $this->sip->curdShow('I'); ?>" data-act="add"><i class="fa fa-plus"></i> Tambah</button></div>
					<div class="col-xs-6 text-right"><button type="button" class="btn btn-danger btn-entry-sub-kegiatan-delete <?php $this->sip->curdShow('D'); ?>"><i class="fa fa-times"></i> Hapus</button></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end sub kegiatan -->




<script>

var blockEntryProgram = '.block-entry-program ';
var blockEntryKegiatan = '.block-entry-kegiatan ';
var blockEntrySubKegiatan = '.block-entry-sub-kegiatan ';

function dataLoadMasterProgram() {
	$(blockEntryKegiatan).hide();
	$(blockEntrySubKegiatan).hide();
	$(blockEntryKegiatan + '.page').val('1');
	var page = $(blockEntryProgram + '.page').val();

	console.log(page);
	$.post('/master/programaster_load/' + page, $(blockEntryProgram + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockEntryProgram + '.data-load').html(res);
		}
	});
}

function dataLoadMasterKegiatan(updateFromDetail) {
	$(blockEntrySubKegiatan).hide();
	$(blockEntrySubKegiatan + '.page').val('1');
	var page = $(blockEntryKegiatan + '.page').val(),
		data = $.extend({},
			$(blockEntryKegiatan + '.form-load').serializeObject(),
			{'f-unitkey' : getVal('#f-unitkey')}
		);
			console.log(page);
	$.post('/master/kegiatanmaster_load/' + page, $(blockEntryKegiatan + '.form-load').serializeArray(), function(res, status, xhr) {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockEntryKegiatan + '.data-load').html(res);
			updateNum(blockEntryKegiatan);

		}
	});
}

// sub kegiatan
function dataLoadMasterSubKegiatan(updateFromDetail) {
	var page = $(blockEntrySubKegiatan + '.page').val(),
		data = $.extend({},
			$(blockEntrySubKegiatan + '.form-load').serializeObject(),
			{'f-kegrkpdkey' : getVal('#f-kegrkpdkey')}
		);

	console.log(page);
	$.post('/master/subkegiatanmaster_load/' + page, data, function(res, status, xhr)  {
		if(contype(xhr) == 'json') {
			respond(res);
		} else {
			$(blockEntrySubKegiatan + '.data-load').html(res);
			updateNum(blockEntrySubKegiatan);
		}
	});
}
// end sub kegiatan


$(function() {
	updateSelect();

	// Program
	$(document).off('change', blockEntryProgram + '#f-unitkey');
	$(document).on('change', blockEntryProgram + '#f-unitkey', function(e) {
		e.preventDefault();
		$(blockEntryProgram + '.page').val('1');
		dataLoadMasterProgram();
	});

	$(document).off('click', blockEntryProgram + '.btn-page');
	$(document).on('click', blockEntryProgram + '.btn-page', function(e) {
		e.preventDefault();
		$(blockEntryProgram + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterProgram();
		return false;
	});

	$(document).off('click', blockEntryProgram + '.btn-program-delete');
	$(document).on('click', blockEntryProgram + '.btn-program-delete', function(e) {
		e.preventDefault();
		if(isEmpty(getVal('#f-unitkey'))) return false;
		if($(blockEntryProgram + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar program yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockEntryProgram + '.form-delete').serializeObject(),
						{'i-unitkey' : getVal('#f-unitkey')}
					);
					$.post('/master/program_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadMasterProgram();
						}
					});
				}
			}
		});

		return false;
	});

	$(document).off('click', blockEntryProgram + '.btn-program-tambah');
	$(document).on('click', blockEntryProgram + '.btn-program-tambah', function(e) {
	  e.preventDefault();
	  if(isEmpty(getVal('#f-unitkey'))) return false;
	  var act = $(this).data('act'),
	    data, title, type;

	  if(act == 'add') {
	    title = 'Entry Program Baru';
	    type = 'type-success';
	    data = {'f-unitkey'	: getVal('#f-unitkey')};
	  } else if(act == 'edit') {
	    title = 'Edit Program';
	    type = 'type-warning';
	    data = {
	      'f-unitkey'		: getVal('#f-unitkey'),
	      'f-pgrmrkpdkey'	: $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()
	    };
	  }

	  modalEntryProgramForm = new BootstrapDialog({
	    title: title,
	    type: type,
	    size: 'size-wide',
	    message: $('<div></div>').load('/master/program_form/' + act, data)
	  });
	  modalEntryProgramForm.open();

	  return false;
	});

	$(document).off('click', blockEntryProgram + '.btn-program-master-show-kegiatan');
	$(document).on('click', blockEntryProgram + '.btn-program-master-show-kegiatan', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockEntryProgram + '.btn-program-master-show-kegiatan').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-pgrmrkpdkey').val(id);
		$(blockEntryKegiatan).fadeIn('fast');
		dataLoadMasterKegiatan();
	});

	$(document).off('submit', blockEntryProgram + '.form-load');
	$(document).on('submit', blockEntryProgram + '.form-load', function(e) {
		e.preventDefault();
		$(blockEntryProgram + '.page').val('1');
		dataLoadMasterProgram();
		return false;
	});

	// Kegiatan
	$(document).off('submit', blockEntryKegiatan + '.form-load');
	$(document).on('submit', blockEntryKegiatan + '.form-load', function(e) {
		e.preventDefault();
		$(blockEntryKegiatan + '.page').val('1');
		dataLoadMasterKegiatan();
		return false;
	});

	$(document).off('click', blockEntryKegiatan + '.btn-page');
	$(document).on('click', blockEntryKegiatan + '.btn-page', function(e) {
		e.preventDefault();
		$(blockEntryKegiatan + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterKegiatan();
		return false;
	});

	$(document).off('click', blockEntryKegiatan + '.btn-entry-kegiatan-form');
	$(document).on('click', blockEntryKegiatan + '.btn-entry-kegiatan-form', function(e) {
		e.preventDefault();
			var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Tambah Kegiatan';
			type = 'type-success';
			data = {'f-pgrmrkpdkey'	: getVal('#f-pgrmrkpdkey')};
		} else if(act == 'edit') {
			title = 'Ubah Kegiatan';
			type = 'type-warning';
			data = 	{'f-kegrkpdkey' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
		}

		modalMasterKegiatanForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/master/kegiatan_form/' + act, data)
		});
		modalMasterKegiatanForm.open();

		return false;
	});

	$(document).off('click', blockEntryKegiatan + '.btn-entry-kegiatan-delete');
	$(document).on('click', blockEntryKegiatan + '.btn-entry-kegiatan-delete', function(e) {
		e.preventDefault();
		if($(blockEntryKegiatan + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar Kegiatan yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockEntryKegiatan + '.form-delete').serializeObject(),
						{'i-pgrmrkpdkey' : getVal('#f-pgrmrkpdkey')}
					);
					$.post('/master/kegiatan_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadMasterKegiatan();
						}
					});
				}
			}
		});

		return false;
	});

	// sub kegiatan
	// show
	$(document).off('click', blockEntryKegiatan + '.btn-kegiatan-master-show-subkegiatan');
	$(document).on('click', blockEntryKegiatan + '.btn-kegiatan-master-show-subkegiatan', function(e) {
		e.preventDefault();
		var id = $(this).closest('tr').find("input[name='i-check[]']:checkbox").val();
		$(blockEntryKegiatan + '.btn-kegiatan-master-show-subkegiatan').removeClass('text-bold text-success');
		$(this).addClass('text-bold text-success');
		$('#f-kegrkpdkey').val(id);
		$(blockEntrySubKegiatan).fadeIn('fast');
		dataLoadMasterSubKegiatan();
	});

	$(document).off('submit', blockEntrySubKegiatan + '.form-load');
	$(document).on('submit', blockEntrySubKegiatan + '.form-load', function(e) {
		e.preventDefault();
		$(blockEntrySubKegiatan + '.page').val('1');
		dataLoadMasterSubKegiatan();
		return false;
	});

	$(document).off('click', blockEntrySubKegiatan + '.btn-page');
	$(document).on('click', blockEntrySubKegiatan + '.btn-page', function(e) {
		e.preventDefault();
		$(blockEntrySubKegiatan + '.page').val($(this).data('ci-pagination-page'));
		dataLoadMasterSubKegiatan();
		return false;
	});
	
	// delete
	$(document).off('click', blockEntrySubKegiatan + '.btn-entry-sub-kegiatan-delete');
	$(document).on('click', blockEntrySubKegiatan + '.btn-entry-sub-kegiatan-delete', function(e) {
		e.preventDefault();
		if($(blockEntrySubKegiatan + ".form-delete input[name='i-check[]']:checkbox:checked").length < 1) {
			return false;
		}
		var id = $(this).closest('tr').data('id');
		goConfirm({
			msg : 'Hapus daftar Sub Kegiatan yang dipilih ?',
			type: 'danger',
			callback : function(ok) {
				if(ok) {
					var data = $.extend({},
						$(blockEntrySubKegiatan + '.form-delete').serializeObject(),
						{'i-kegrkpdkey' : getVal('#f-kegrkpdkey')}
					);
					$.post('/master/subkegiatan_delete/', data, function(res, status, xhr) {
						if(contype(xhr) == 'json') {
							respond(res);
						} else {
							dataLoadMasterSubKegiatan();
						}
					});
				}
			}
		});

		return false;
	});

	// add
	$(document).off('click', blockEntrySubKegiatan + '.btn-entry-sub-kegiatan-form');
	$(document).on('click', blockEntrySubKegiatan + '.btn-entry-sub-kegiatan-form', function(e) {
		e.preventDefault();
			var act = $(this).data('act'),
			data, title, type;

		if(act == 'add') {
			title = 'Tambah Sub Kegiatan';
			type = 'type-success';
			data = {'f-kegrkpdkey'	: getVal('#f-kegrkpdkey')};
		} else if(act == 'edit') {
			title = 'Ubah Sub Kegiatan';
			type = 'type-warning';
			data = 	{'f-subkegrkpdkey' : $(this).closest('tr').find("input[name='i-check[]']:checkbox").val()};
		}

		modalMasterSubKegiatanForm = new BootstrapDialog({
			title: title,
			type: type,
			size: 'size-wide',
			message: $('<div></div>').load('/master/subkegiatan_form/' + act, data)
		});
		modalMasterSubKegiatanForm.open();

		return false;
	});
	// end sub kegiatan

});
</script>
